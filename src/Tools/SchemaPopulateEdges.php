<?php

namespace TheDonHimself\GremlinOGM\Tools;

class SchemaPopulateEdges
{
    public function populate(array $edges)
    {
        $edges_commands = array();

        foreach ($edges as $label => $properties_array) {
            $properties = implode(', ', $properties_array);
            $property_keys_commands[] = "graph.addVertex(label, '$label', $properties)";
        }

        return $edges_commands;
    }
}
