<?php

namespace TheDonHimself\GremlinOGM\Tools;

use Brightzone\GremlinDriver\Connection;
use Brightzone\GremlinDriver\InternalException;
use Brightzone\GremlinDriver\ServerException;

class SchemaCreate
{
    public function create(array $schema)
    {
        $graph_property_keys = $schema['properties'];
        $graph_vertexes = $schema['vertexes'];
        $graph_edges = $schema['edges'];
        $graph_indexes = $schema['indexes'];

        $property_keys_commands = array();
        foreach ($graph_property_keys as $property_key => $properties) {
            $type = $properties['type'];
            $cardinality = $properties['cardinality'];

            $property_keys_commands[] = "$property_key = mgmt.makePropertyKey('$property_key').dataType(" . $type . ".class).cardinality(Cardinality." . $cardinality . ").make()";
        }

        $vertexes_commands = array();
        foreach ($graph_vertexes as $label => $boolean) {
            $vertexes_commands[] = "$label = mgmt.makeVertexLabel('$label').make()";
        }

        $edges_commands = array();
        foreach ($graph_edges as $label => $multiplicity) {
            $edges_commands[] = "$label = mgmt.makeEdgeLabel('$label').multiplicity($multiplicity).make()";
        }

        $indexes_commands = array();
        foreach ($graph_indexes as $index) {
            $name = $index['name'];
            $label = $index['label'];
            $keys = $index['keys'];

            $type = $index['type'];
            if ($type == 'Composite') {
                $class = $index['class'];
                $unique = $index['unique'];
                $label_constraint = $index['label_constraint'];

                $add_keys = '';
                foreach ($keys as $add_key) {
                    $add_keys = $add_keys.'.addKey('.$add_key.')';
                }

                $index = "mgmt";

                $index = $index . ".buildIndex('$name', $class)$add_keys";

                if($unique){
                    $index = $index . ".unique()";
                }

                if($label_constraint){
                    $index = $index . ".indexOnly($label)";
                }

                $index = $index . ".buildCompositeIndex()";

                $indexes_commands[] = $index;
            }
            if ($type == 'Mixed') {
                $class = $index['class'];
                $label_constraint = $index['label_constraint'];

                $add_keys = '';
                foreach ($keys as $add_key => $mapping) {
                    $add_keys = $add_keys . '.addKey(' . $add_key . ', Mapping.' . $mapping . '.asParameter())';
                }

                $index = "mgmt";

                $index = $index . ".buildIndex('$name', $class)$add_keys";

                if($label_constraint){
                    $index = $index . ".indexOnly($label)";
                }

                $index = $index . ".buildMixedIndex(\"search\")";

                $indexes_commands[] = $index;
            }
            if ($type == 'Vertex-centric') {
                $direction = $index['direction'];
                $order = $index['order'];

                $add_keys = implode(', ', $keys);

                $index = "mgmt";

                $index = $index . ".buildEdgeIndex($label, '$name', Direction." . $direction . ", Order." . $order . ", $add_keys)";

                $indexes_commands[] = $index;
            }
        }

        $commands = array_merge($property_keys_commands, $vertexes_commands, $edges_commands, $indexes_commands);

        return $commands;
    }
}
