<?php

namespace TheDonHimself\GremlinOGM\Tools;

use TheDonHimself\GremlinOGM\Serializer\GraphSerializer;

class SchemaPopulateVertexes
{
    public function populate(array $vertexes)
    {
        $graph_serializer = new GraphSerializer();

        $vertexes_commands = array();

        foreach ($vertexes as $label => $vertex_properties) {
            $string = $graph_serializer->toString($vertex_properties);
            if ($string) {
                $vertexes_commands[] = "graph.addVertex(label, '$label', $string)";
            } else {
                $vertexes_commands[] = "graph.addVertex(label, '$label')";
            }
        }

        return $vertexes_commands;
    }
}
