<?php

namespace TheDonHimself\GremlinOGM\Tools;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use ReflectionClass;
use ReflectionMethod;
use ReflectionProperty;
use TheDonHimself\GremlinOGM\Exception\PopulateEdgesException;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use TheDonHimself\GremlinOGM\Annotation as Graph;

class EdgesPopulate
{
    public function properties(string $class)
    {
        AnnotationRegistry::registerLoader('class_exists');

        $annotationReader = new AnnotationReader();

        $graph_add_from_vertex_annotation = 'TheDonHimself\GremlinOGM\Annotation\AddEdgeFromVertex';
        $graph_add_to_vertex_annotation = 'TheDonHimself\GremlinOGM\Annotation\AddEdgeToVertex';

        $graph_edge_annotation = 'TheDonHimself\GremlinOGM\Annotation\Edge';

        //Get class annotation
        $reflectionClass = new ReflectionClass($class);

        $class_properties = $reflectionClass->getProperties();

        $property_names = array();
        foreach ($class_properties as $class_property) {
            $property_name = $class_property->getName();
            $property_names[] = $property_name;
        }

        $edgeAnnotations = $annotationReader->getClassAnnotation($reflectionClass, $graph_edge_annotation);
        if (!$edgeAnnotations) {
            return;
        }

        $label = $edgeAnnotations->label;

        $graph_add_from_vertex = false;
        $graph_add_to_vertex = false;

        $add_from_vertex = array();
        $add_to_vertex = array();

        foreach ($property_names as $property_name) {
            $reflectionProperty = new ReflectionProperty($class, $property_name);

            $propertyAddFromVertexAnnotation = $annotationReader->getPropertyAnnotation($reflectionProperty, $graph_add_from_vertex_annotation);
            $propertyAddToVertexAnnotation = $annotationReader->getPropertyAnnotation($reflectionProperty, $graph_add_to_vertex_annotation);

            if ($propertyAddFromVertexAnnotation) {
                if ($graph_add_from_vertex === true) {
                    throw new PopulateEdgesException($class, 'Currently, a class can only have one @AddEdgeFromVertex annotation');
                }
                $graph_add_from_vertex = true;
                $add_from_vertex['label'] = $propertyAddFromVertexAnnotation->targetVertex;
                $add_from_vertex['uniquePropertyKey'] = $propertyAddFromVertexAnnotation->uniquePropertyKey;
                $add_from_vertex['methodsForKeyValue'] = $propertyAddFromVertexAnnotation->methodsForKeyValue;
            }

            if ($propertyAddToVertexAnnotation) {
                if ($graph_add_to_vertex === true) {
                    throw new PopulateEdgesException($class, 'Currently, a class can only have one @AddEdgeToVertex annotation');
                }
                $graph_add_to_vertex = true;
                $add_to_vertex['label'] = $propertyAddToVertexAnnotation->targetVertex;
                $add_to_vertex['uniquePropertyKey'] = $propertyAddToVertexAnnotation->uniquePropertyKey;
                $add_to_vertex['methodsForKeyValue'] = $propertyAddToVertexAnnotation->methodsForKeyValue;
            }
        }

        if ($graph_add_from_vertex === false && $graph_add_to_vertex === false) {
            return;
        }

        if (($graph_add_from_vertex === true && $graph_add_to_vertex === false) || ($graph_add_from_vertex === false && $graph_add_to_vertex === true)) {
            throw new PopulateEdgesException($class, 'A class must have both @AddEdgeFromVertex and @AddEdgeToVertex annotations but only found one');
        }

        return array(
            '_phpclass' => $class,
            'label' => $label,
            'from' => $add_from_vertex,
            'to' => $add_to_vertex,
        );
    }

    public function methods(string $class)
    {
        AnnotationRegistry::registerLoader('class_exists');

        $annotationReader = new AnnotationReader();

        $graph_populate_edges_annotation = 'TheDonHimself\GremlinOGM\Annotation\PopulateEdges';
        $graph_vertex_annotation = 'TheDonHimself\GremlinOGM\Annotation\Vertex';

        //Get class annotation
        $reflectionClass = new ReflectionClass($class);

        $class_methods = $reflectionClass->getMethods();

        $method_names = array();
        foreach ($class_methods as $class_method) {
            $method_name = $class_method->getName();
            $method_names[] = $method_name;
        }

        $vertexAnnotations = $annotationReader->getClassAnnotation($reflectionClass, $graph_vertex_annotation);
        if (!$vertexAnnotations) {
            return;
        }

        $label = $vertexAnnotations->label;

        $graph_add_to_vertex_methods = false;
        $method = null;

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
            $method = $method_name;
        }

        if ($graph_add_to_vertex_methods !== true) {
            return;
        }

        return array(
            '_phpclass' => $class,
            'method' => $method,
        );
    }

    public function embedded(string $class)
    {
        AnnotationRegistry::registerLoader('class_exists');

        $annotationReader = new AnnotationReader();

        $graph_embedded_edges_annotation = 'TheDonHimself\GremlinOGM\Annotation\EmbeddedEdges';
        $graph_embedded_edge_annotation = 'TheDonHimself\GremlinOGM\Annotation\EmbeddedEdge';

        //Get class annotation
        $reflectionClass = new ReflectionClass($class);

        $embeddedEdgesAnnotations = $annotationReader->getClassAnnotation($reflectionClass, $graph_embedded_edges_annotation);

        if (!$embeddedEdgesAnnotations) {
            return;
        }

        $embedded_edges_array = array();

        foreach ($embeddedEdgesAnnotations->value as $embeddedEdgeAnnotation){
            $array = array();

            foreach ($embeddedEdgeAnnotation->value as $annotationObject){
                if($annotationObject instanceof Graph\EmbeddedEdgeProperty){
                    $field = $annotationObject->field;
                    $array['field'] = $field;

                    if(property_exists($class, $field) !== true){
                        throw new PopulateEdgesException($class, 'An embedded edge class must have a field property but field name : ' . $field . ' was not found.');
                    }

                    $get_field = 'get' . ucfirst($field);

                    if(method_exists($class, $get_field) !== true){
                        throw new PopulateEdgesException($class, 'An embedded edge class must have a get method to retrieve embedable fields property but getMethod : ' . $get_field . ' was not found.');
                    }

                    $embed = null;

                    $embed_one_annotation = 'Doctrine\ODM\MongoDB\Mapping\Annotations\EmbedOne';
                    $embed_many_annotation = 'Doctrine\ODM\MongoDB\Mapping\Annotations\EmbedMany';

                    $reflectionProperty = new ReflectionProperty($class, $field);

                    $propertyEmbedOneAnnotation = $annotationReader->getPropertyAnnotation($reflectionProperty, $embed_one_annotation);
                    if($propertyEmbedOneAnnotation){
                        $embed = 'EmbedOne';
                    }

                    $propertyEmbedManyAnnotation = $annotationReader->getPropertyAnnotation($reflectionProperty, $embed_many_annotation);
                    if($propertyEmbedManyAnnotation){
                        $embed = 'EmbedMany';
                    }

                    if($embed == null){
                        throw new PopulateEdgesException($class, 'An embedded edge class field must have a doctrine annotation @EmbedOne or @EmbedMany method to retrieve a single embeddable Doc or a collection. No such annotation was found for field ' . $field);
                    }

                    $array['embed'] = $embed;
                }
                if($annotationObject instanceof Graph\AddEdgeFromVertex){
                    $add_from_vertex = array();
                    $add_from_vertex['label'] = $annotationObject->targetVertex;
                    $add_from_vertex['uniquePropertyKey'] = $annotationObject->uniquePropertyKey;
                    $add_from_vertex['methodsForKeyValue'] = $annotationObject->methodsForKeyValue;
                    $array['from'] = $add_from_vertex;
                }
                if($annotationObject instanceof Graph\AddEdgeToVertex){
                    $add_to_vertex = array();
                    $add_to_vertex['label'] = $annotationObject->targetVertex;
                    $add_to_vertex['uniquePropertyKey'] = $annotationObject->uniquePropertyKey;
                    $add_to_vertex['methodsForKeyValue'] = $annotationObject->methodsForKeyValue;
                    $array['to'] = $add_to_vertex;
                }
            }

            if (!isset($array['field']) || !isset($array['embed']) || !isset($array['from']) || !isset($array['to'])){
                throw new PopulateEdgesException($class, 'An embedded edge class must have all @EmbeddedEdgeProperty, @AddEdgeFromVertex and @AddEdgeToVertex annotations');
            }

            $embedded_edges_array[] = $array;
        }

        return array(
            '_phpclass' => $class,
            'embedded_edges' => $embedded_edges_array
        );
    }

    public function populate(array $class_maps)
    {
        AnnotationRegistry::registerLoader('class_exists');

        $graph_populate_edges_embedded = array();
        $graph_populate_edges_properties = array();
        $graph_populate_edges_methods = array();

        foreach ($class_maps as $class => $class_path) {
            $propertyEdges = self::properties($class);
            if($propertyEdges){
                $graph_populate_edges_properties[] = $propertyEdges;
            }

            $methodEdges = self::methods($class);
            if($methodEdges){
                $graph_populate_edges_methods[] = $methodEdges;
            }

            $embeddedEdges = self::embedded($class);
            if($embeddedEdges){
                $graph_populate_edges_embedded[] = $embeddedEdges;
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
