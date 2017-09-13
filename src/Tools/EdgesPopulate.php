<?php

namespace TheDonHimself\GremlinOGM\Tools;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use ReflectionClass;
use ReflectionMethod;
use ReflectionProperty;
use TheDonHimself\GremlinOGM\Exception\PopulateEdgesException;

class EdgesPopulate
{
    public function populate(array $class_maps)
    {
        AnnotationRegistry::registerLoader('class_exists');

        $annotationReader = new AnnotationReader();

        $graph_populate_edges_annotation = 'TheDonHimself\GremlinOGM\Annotation\PopulateEdges';

        $graph_add_from_vertex_annotation = 'TheDonHimself\GremlinOGM\Annotation\AddEdgeFromVertex';
        $graph_add_to_vertex_annotation = 'TheDonHimself\GremlinOGM\Annotation\AddEdgeToVertex';

        $graph_embedded_edges_annotation = 'TheDonHimself\GremlinOGM\Annotation\EmbeddedEdges';
        $graph_embedded_edge_annotation = 'TheDonHimself\GremlinOGM\Annotation\EmbeddedEdge';

        $graph_vertex_annotation = 'TheDonHimself\GremlinOGM\Annotation\Vertex';
        $graph_edge_annotation = 'TheDonHimself\GremlinOGM\Annotation\Edge';

        $graph_populate_edges_embedded = array();
        $graph_populate_edges_properties = array();
        $graph_populate_edges_methods = array();

        foreach ($class_maps as $class => $class_path) {
            //Get class annotation
            $reflectionClass = new ReflectionClass($class);

            $embeddedEdgesAnnotations = $annotationReader->getClassAnnotation($reflectionClass, $graph_embedded_edges_annotation);
            if ($embeddedEdgesAnnotations) {
                $graph_populate_edges_embedded[] = array(
                    '_phpclass' => $class,
                );
            }

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

            $edgeAnnotations = $annotationReader->getClassAnnotation($reflectionClass, $graph_edge_annotation);
            if ($edgeAnnotations) {
                $label = $edgeAnnotations->label;

                $graph_add_from_vertex = false;
                $graph_add_to_vertex = false;

                foreach ($property_names as $property_name) {
                    $reflectionProperty = new ReflectionProperty($class, $property_name);

                    $propertyAddFromVertexAnnotation = $annotationReader->getPropertyAnnotation($reflectionProperty, $graph_add_from_vertex_annotation);
                    $propertyAddToVertexAnnotation = $annotationReader->getPropertyAnnotation($reflectionProperty, $graph_add_to_vertex_annotation);

                    if ($propertyAddFromVertexAnnotation) {
                        if ($graph_add_from_vertex === true) {
                            throw new PopulateEdgesException($class, 'Currently, a class can only have one @AddEdgeFromVertex annotation');
                        }
                        $graph_add_from_vertex = true;
                        $add_from_vertex = array();
                        $add_from_vertex['label'] = $propertyAddFromVertexAnnotation->targetVertex;
                        $add_from_vertex['uniquePropertyKey'] = $propertyAddFromVertexAnnotation->uniquePropertyKey;
                        $add_from_vertex['methodsForKeyValue'] = $propertyAddFromVertexAnnotation->methodsForKeyValue;
                    }

                    if ($propertyAddToVertexAnnotation) {
                        if ($graph_add_to_vertex === true) {
                            throw new PopulateEdgesException($class, 'Currently, a class can only have one @AddEdgeToVertex annotation');
                        }
                        $graph_add_to_vertex = true;
                        $add_to_vertex = array();
                        $add_to_vertex['label'] = $propertyAddToVertexAnnotation->targetVertex;
                        $add_to_vertex['uniquePropertyKey'] = $propertyAddToVertexAnnotation->uniquePropertyKey;
                        $add_to_vertex['methodsForKeyValue'] = $propertyAddToVertexAnnotation->methodsForKeyValue;
                    }
                }

                if (($graph_add_from_vertex === true && $graph_add_to_vertex === false) || ($graph_add_from_vertex === false && $graph_add_to_vertex === true)) {
                    throw new PopulateEdgesException($class, 'A class must have both @AddEdgeFromVertex and @AddEdgeToVertex annotations but only found one');
                }

                if ($graph_add_from_vertex === true && $graph_add_to_vertex === true) {
                    $graph_populate_edges_properties[] = array(
                        '_phpclass' => $class,
                        'label' => $label,
                        'from' => $add_from_vertex,
                        'to' => $add_to_vertex,
                    );
                }
            }

            $vertexAnnotations = $annotationReader->getClassAnnotation($reflectionClass, $graph_vertex_annotation);
            if ($vertexAnnotations) {
                $label = $vertexAnnotations->label;

                $graph_add_to_vertex_methods = false;

                foreach ($method_names as $method_name) {
                    $reflectionMethod = new ReflectionMethod($class, $method_name);
                    $populateEdgesAnnotation = $annotationReader->getMethodAnnotation($reflectionMethod, $graph_populate_edges_annotation);

                    if (!$populateEdgesAnnotation) {
                        continue;
                    }

                    if ($graph_add_to_vertex_methods === true) {
                        throw new PopulateEdgesException($class, 'Currently, a class can only have one @PopulateEdges method');
                    }

                    $graph_add_to_vertex_methods = true;

                    $graph_populate_edges_methods[] = array(
                        '_phpclass' => $class,
                    );
                }
            }
        }

        $classes = array(
            'properties' => $graph_populate_edges_properties,
            'methods' => $graph_populate_edges_methods,
            'embedded' => $graph_populate_edges_embedded,
        );

        return $classes;
    }
}
