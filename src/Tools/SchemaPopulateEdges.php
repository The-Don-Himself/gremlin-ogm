<?php

namespace TheDonHimself\GremlinOGM\Tools;

class SchemaPopulateEdges
{
    public function populate($edge_label, $from_vertex, $to_vertex, $properties_string = null)
    {
        return "$from_vertex.addEdge('$edge_label', $to_vertex)".$properties_string;
        $command_string = $properties_string ? "$from_vertex.addEdge('$edge_label', $to_vertex, $properties_string)" : "$from_vertex.addEdge('$edge_label', $to_vertex)";
//        $command_string = "g.addE('$edge_label').from($from_vertex).to($to_vertex)" . $properties_string;
        return $command_string;
    }
}
