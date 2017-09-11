<?php

namespace TheDonHimself\GremlinOGM\Tools;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use ReflectionClass;
use ReflectionMethod;
use ReflectionProperty;
use TheDonHimself\GremlinOGM\Exception\IndexNamesConflictException;
use TheDonHimself\GremlinOGM\Exception\PropertyKeyMismatchException;
use TheDonHimself\GremlinOGM\Exception\VertexLabelsConflictException;

class SchemaCheck
{
    public function check(array $class_maps)
    {
        AnnotationRegistry::registerLoader('class_exists');

        $annotationReader = new AnnotationReader();

        $graph_property_name_annotation = 'TheDonHimself\GremlinOGM\Annotation\PropertyName';
        $graph_property_type_annotation = 'TheDonHimself\GremlinOGM\Annotation\PropertyType';
        $graph_property_cardinality_annotation = 'TheDonHimself\GremlinOGM\Annotation\PropertyCardinality';
        $graph_vertex_annotation = 'TheDonHimself\GremlinOGM\Annotation\Vertex';
        $graph_edge_annotation = 'TheDonHimself\GremlinOGM\Annotation\Edge';

        $graph_property_keys = array();
        $graph_vertexes = array();
        $graph_edges = array();
        $graph_indexes = array();

        foreach ($class_maps as $class => $class_path) {
            //Get class annotation
            $reflectionClass = new ReflectionClass($class);

            $class_properties = $reflectionClass->getProperties();
            $class_methods = $reflectionClass->getMethods();

            $property_names = array();
            foreach ($class_properties as $class_property) {
                $property_name = $class_property->getName();
                $property_names[] = $property_name;
            }

            $method_names = array();
            foreach ($class_methods as $class_method) {
                $method_name = $class_method->getName();
                $method_names[] = $method_name;
            }

            foreach ($property_names as $property_name) {
                $reflectionProperty = new ReflectionProperty($class, $property_name);
                $propertyNameAnnotation = $annotationReader->getPropertyAnnotation($reflectionProperty, $graph_property_name_annotation);
                $propertyTypeAnnotation = $annotationReader->getPropertyAnnotation($reflectionProperty, $graph_property_type_annotation);
                $propertyCardinalityAnnotation = $annotationReader->getPropertyAnnotation($reflectionProperty, $graph_property_cardinality_annotation);
                if ($propertyNameAnnotation && $propertyTypeAnnotation && $propertyCardinalityAnnotation) {
                    $property_key_name = $propertyNameAnnotation->name;
                    if (isset($graph_property_keys[$property_key_name])) {
                        $type = $graph_property_keys[$property_key_name]['type'];
                        $cardinality = $graph_property_keys[$property_key_name]['cardinality'];
                        if ($type !== $propertyTypeAnnotation->name || $cardinality !== $propertyCardinalityAnnotation->name) {
                            throw new PropertyKeyMismatchException(
                                $property_key_name,
                                $type,
                                $cardinality,
                                $propertyTypeAnnotation->name,
                                $propertyCardinalityAnnotation->name,
                                $class
                            );
                        }
                    }
                    $graph_property_keys[$propertyNameAnnotation->name] = array(
                        'type' => $propertyTypeAnnotation->name,
                        'cardinality' => $propertyCardinalityAnnotation->name,
                    );
                }
            }
            foreach ($method_names as $method_name) {
                $reflectionMethod = new ReflectionMethod($class, $method_name);
                $methodNameAnnotation = $annotationReader->getMethodAnnotation($reflectionMethod, $graph_property_name_annotation);
                $methodTypeAnnotation = $annotationReader->getMethodAnnotation($reflectionMethod, $graph_property_type_annotation);
                $methodCardinalityAnnotation = $annotationReader->getMethodAnnotation($reflectionMethod, $graph_property_cardinality_annotation);
                if ($methodNameAnnotation && $methodTypeAnnotation && $methodCardinalityAnnotation) {
                    $property_key_name = $methodNameAnnotation->name;
                    if (isset($graph_property_keys[$property_key_name])) {
                        $type = $graph_property_keys[$property_key_name]['type'];
                        $cardinality = $graph_property_keys[$property_key_name]['cardinality'];
                        if ($type !== $methodTypeAnnotation->name || $cardinality !== $methodCardinalityAnnotation->name) {
                            throw new PropertyKeyMismatchException(
                                $property_key_name,
                                $type,
                                $cardinality,
                                $methodTypeAnnotation->name,
                                $methodCardinalityAnnotation->name,
                                $class
                            );
                        }
                    }
                    $graph_property_keys[$property_key_name] = array(
                        'type' => $methodTypeAnnotation->name,
                        'cardinality' => $methodCardinalityAnnotation->name,
                    );
                }
            }

            $vertexAnnotations = $annotationReader->getClassAnnotation($reflectionClass, $graph_vertex_annotation);
            if ($vertexAnnotations) {
                $label = $vertexAnnotations->label;
                if (isset($graph_vertexes[$label])) {
                    throw new VertexLabelsConflictException($label, $class);
                }
                $graph_vertexes[$label] = array(
                    '_phpclass' => $class,
                );
                $indexes = $vertexAnnotations->indexes;
                foreach ($indexes as $index) {
                    $name = $index->name;
                    $type = $index->type;
                    if (isset($graph_indexes[$name])) {
                        throw new IndexNamesConflictException($name, $class);
                    }
                    if ($type == 'Composite') {
                        $graph_indexes[$name] = array(
                            'name' => $index->name,
                            'label' => $label,
                            'class' => 'Vertex.class',
                            'type' => $index->type,
                            'unique' => $index->unique,
                            'label_constraint' => $index->label_constraint,
                            'keys' => $index->keys,
                        );
                    }
                    if ($type == 'Mixed') {
                        $graph_indexes[$name] = array(
                            'name' => $index->name,
                            'label' => $label,
                            'class' => 'Vertex.class',
                            'type' => $index->type,
                            'label_constraint' => $index->label_constraint,
                            'keys' => $index->keys,
                        );
                    }
                    if ($type == 'Vertex-centric') {
                        $graph_indexes[$name] = array(
                            'name' => $index->name,
                            'label' => $label,
                            'type' => $index->type,
                            'direction' => $index->direction,
                            'order' => $index->order,
                            'keys' => $index->keys,
                        );
                    }
                }
            }

            $edgeAnnotations = $annotationReader->getClassAnnotation($reflectionClass, $graph_edge_annotation);
            if ($edgeAnnotations) {
                $label = $edgeAnnotations->label;
                $multiplicity = $edgeAnnotations->multiplicity;
                if (isset($graph_edges[$label])) {
                    $message = '';
                    $message = $message.PHP_EOL;
                    $message = $message.'========== NOTICE =========='.PHP_EOL;
                    $message = $message.'Overwriting Edge                 : '.$label.PHP_EOL;
                    $message = $message.'Class                            : '.$class.PHP_EOL;
                    $message = $message.'============================'.PHP_EOL;
                    $message = $message.PHP_EOL;
                    trigger_error($message, E_USER_NOTICE);
                }
                $graph_edges[$label] = array(
                    '_phpclass' => $class,
                    'multiplicity' => $multiplicity,
                );
                $indexes = $edgeAnnotations->indexes;
                foreach ($indexes as $index) {
                    $name = $index->name;
                    $type = $index->type;
                    if (isset($graph_indexes[$name])) {
                        continue;
                    }
                    if ($type == 'Composite') {
                        $graph_indexes[$name] = array(
                            'name' => $index->name,
                            'label' => $label,
                            'class' => 'Edge.class',
                            'type' => $index->type,
                            'unique' => $index->unique,
                            'label_constraint' => $index->label_constraint,
                            'keys' => $index->keys,
                        );
                    }
                    if ($type == 'Mixed') {
                        $graph_indexes[$name] = array(
                            'name' => $index->name,
                            'label' => $label,
                            'class' => 'Edge.class',
                            'type' => $index->type,
                            'label_constraint' => $index->label_constraint,
                            'keys' => $index->keys,
                        );
                    }
                    if ($type == 'Vertex-centric') {
                        $graph_indexes[$name] = array(
                            'name' => $index->name,
                            'label' => $label,
                            'type' => $index->type,
                            'direction' => $index->direction,
                            'order' => $index->order,
                            'keys' => $index->keys,
                        );
                    }
                }
            }
        }

        $schema = array(
            'properties' => $graph_property_keys,
            'vertexes' => $graph_vertexes,
            'edges' => $graph_edges,
            'indexes' => $graph_indexes,
        );

        return $schema;
    }
}
