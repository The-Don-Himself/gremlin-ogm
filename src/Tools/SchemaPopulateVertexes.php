<?php

namespace TheDonHimself\GremlinOGM\Tools;

class SchemaPopulateVertexes
{
    public function populate(array $vertexes)
    {
        $vertexes_commands = array();

        foreach ($vertexes as $label => $vertex_properties) {
            $properties_array = array();
            foreach ($vertex_properties as $key => &$value) {
                if(is_string($value)){
                    $value = addslashes($value);
                    $value = "'$value'";
                }
                $properties_array[] = "'$key'";
                $properties_array[] = "$value";
            }
            if($properties_array){
                $properties = implode(', ', $properties_array);
                $vertexes_commands[] = "graph.addVertex(label, '$label', $properties)";
            } else {
                $vertexes_commands[] = "graph.addVertex(label, '$label')";
            }
        }

        return $vertexes_commands;
    }
}
