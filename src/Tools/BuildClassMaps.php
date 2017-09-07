<?php

namespace TheDonHimself\GremlinOGM\Tools;

use Symfony\Component\ClassLoader\ClassMapGenerator;

class BuildClassMaps
{
    public function build(string $appBundle)
    {
        $graphPath = $appBundle.'/Graph';

        $verticesPath = $graphPath.'/Vertices';
        $edgesPath = $graphPath.'/Edges';

        $entitiesPath = $appBundle.'/Entity';
        $documentsPath = $appBundle.'/Document';

        $vertices_class_maps = is_dir($verticesPath) ? ClassMapGenerator::createMap($verticesPath) : array();
        $edges_class_maps = is_dir($edgesPath) ? ClassMapGenerator::createMap($edgesPath) : array();
        $entities_class_maps = is_dir($entitiesPath) ? ClassMapGenerator::createMap($entitiesPath) : array();
        $documents_class_maps = is_dir($documentsPath) ? ClassMapGenerator::createMap($documentsPath) : array();

        $class_maps = array_merge($vertices_class_maps, $edges_class_maps, $entities_class_maps, $documents_class_maps);

        return $class_maps;
    }
}
