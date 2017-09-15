<?php

namespace TheDonHimself\GremlinOGM\Command;

use TheDonHimself\GremlinOGM\Serializer\GraphSerializer;

class ObjectToEdge
{
    public function getCommand(string $edge_label, array $from_vertex, array $to_vertex, $object)
    {
        $from_vertex_label = $from_vertex['label'];
        $from_vertex_key = $from_vertex['uniquePropertyKey'];
        $from_vertex_value_methods = $from_vertex['methodsForKeyValue'];

        $to_vertex_label = $to_vertex['label'];
        $to_vertex_key = $to_vertex['uniquePropertyKey'];
        $to_vertex_value_methods = $to_vertex['methodsForKeyValue'];

        $continue = false;

        $from_vertex_value = $object;
        foreach ($from_vertex_value_methods as $method) {
            $from_vertex_value = call_user_func(array($from_vertex_value, $method));
            if (!$from_vertex_value) {
                $continue = true;
                break;
            }
        }

        if ($continue === true) {
            return;
        }

        if (is_string($from_vertex_value)) {
            $from_vertex_value = trim(preg_replace('/\s+/', ' ', $from_vertex_value));
            $from_vertex_value = addslashes($from_vertex_value);
            $from_vertex_value = "'$from_vertex_value'";
        }

        if (is_bool($from_vertex_value)) {
            $from_vertex_value = $from_vertex_value ? 'true' : 'false';
        }

        $to_vertex_value = $object;
        foreach ($to_vertex_value_methods as $method) {
            $to_vertex_value = call_user_func(array($to_vertex_value, $method));
            if (!$from_vertex_value) {
                $continue = true;
                break;
            }
        }

        if ($continue === true) {
            return;
        }

        if (is_string($to_vertex_value)) {
            $to_vertex_value = trim(preg_replace('/\s+/', ' ', $to_vertex_value));
            $to_vertex_value = addslashes($to_vertex_value);
            $to_vertex_value = "'$to_vertex_value'";
        }

        if (is_bool($to_vertex_value)) {
            $to_vertex_value = $to_vertex_value ? 'true' : 'false';
        }

        $graph_serializer = new GraphSerializer();

        $properties = $graph_serializer->toArray($object);
        $properties_string = $graph_serializer->toString($properties);
        if ($properties_string) {
            $properties_string = ', '.$properties_string;
        }

        $add_edge_from_vertex = "g.V().hasLabel('$from_vertex_label').has('$from_vertex_key', $from_vertex_value)";
        $add_edge_to_vertex = "g.V().hasLabel('$to_vertex_label').has('$to_vertex_key', $to_vertex_value)";

        return "if ($add_edge_from_vertex.hasNext() == true && $add_edge_to_vertex.hasNext() == true) { $add_edge_from_vertex.next().addEdge('$edge_label', $add_edge_to_vertex.next()$properties_string) }";

        $add_edge_from_vertex = "g.V().hasLabel('$from_vertex_label').has('$from_vertex_key', $from_vertex_value).next()";
        $add_edge_to_vertex = "g.V().hasLabel('$to_vertex_label').has('$to_vertex_key', $to_vertex_value).next()";

        return "$add_edge_from_vertex.addEdge('$edge_label', $add_edge_to_vertex)".$properties_string;
    }
}
