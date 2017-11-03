<?php

namespace TheDonHimself\GremlinOGM\Serializer;

use JMS\Serializer\Handler\HandlerRegistry;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerBuilder;
use TheDonHimself\GremlinOGM\Exception\UnserializableException;
use TheDonHimself\GremlinOGM\Serializer\Handler\SerializerDateSubscriber;

class GraphSerializer
{
    public function getSerializer()
    {
        $serializer = SerializerBuilder::create()
            ->addDefaultHandlers()
            ->configureHandlers(function (HandlerRegistry $registry) {
                $registry->registerSubscribingHandler(new SerializerDateSubscriber());
            })
            ->build();

        return $serializer;
    }

    public function toArray($object)
    {
        $serializer = $this->getSerializer();

        $array = $serializer->toArray($object, SerializationContext::create()->setGroups(array('Graph')));

        return $array;
    }

    public function toString(array $array)
    {
        $string_array = array();

        foreach ($array as $key => $value) {
            if (is_object($value)) {
                throw new UnserializableException($key, 'Cannot Serialize Objects To String Please Convert To Array First');
            }

            if (is_string($value)) {
                $value = json_encode($value, JSON_UNESCAPED_SLASHES);
            }

            if (is_bool($value)) {
                $value = $value ? 'true' : 'false';
            }

            if (!is_array($value)) {
                $string_array[] = "'$key'";
                $string_array[] = "$value";
                continue;
            }

            foreach ((array) $value as $value_key => $value_value) {
                if ('_geoshapepoint' === $value_key) {
                    $lat = isset($value_value['lat']) ? $value_value['lat'] : null;
                    $lon = isset($value_value['lon']) ? $value_value['lon'] : null;

                    if ($lat && $lon) {
                        $value_value = "Geoshape.point($lat, $lon)";
                    } else {
                        $value_value = 'null';
                    }

                    $string_array[] = "'$key'";
                    $string_array[] = $value_value;
                    continue;
                }

                if ('_geoshapecircle' === $value_key) {
                    $lat = isset($value_value['lat']) ? $value_value['lat'] : null;
                    $lon = isset($value_value['lon']) ? $value_value['lon'] : null;
                    $radius = isset($value_value['radius']) ? $value_value['radius'] : null;
                    if ($lat && $lon && $radius) {
                        $value_value = "Geoshape.circle($lat, $lon, $radius)";
                    } else {
                        $value_value = 'null';
                    }

                    $string_array[] = "'$key'";
                    $string_array[] = $value_value;
                    continue;
                }

                if ('_geoshapebox' === $value_key) {
                    $sw_lat = isset($value_value['sw_lat']) ? $value_value['sw_lat'] : null;
                    $sw_lon = isset($value_value['sw_lon']) ? $value_value['sw_lon'] : null;
                    $ne_lat = isset($value_value['ne_lat']) ? $value_value['ne_lat'] : null;
                    $ne_lon = isset($value_value['ne_lon']) ? $value_value['ne_lon'] : null;

                    if ($sw_lat && $sw_lon && $ne_lat && $ne_lon) {
                        $value_value = "Geoshape.box($sw_lat, $sw_lon, $ne_lat, $ne_lon)";
                    } else {
                        $value_value = 'null';
                    }

                    $string_array[] = "'$key'";
                    $string_array[] = $value_value;
                    continue;
                }

                if ('_map_of' === $value_key) {
                    $mapOf = $this->toString($value_value);

                    if ($mapOf) {
                        $value_value = "Map.of($mapOf)";
                    } else {
                        $value_value = 'null';
                    }

                    $string_array[] = "'$key'";
                    $string_array[] = $value_value;
                    continue;
                }

                if ('_list_collection' === $value_key) {
                    $listCollection = implode(', ', $value_value);

                    if ($listCollection) {
                        $value_value = "[ $listCollection ]";
                    } else {
                        $value_value = 'null';
                    }

                    $string_array[] = "'$key'";
                    $string_array[] = $value_value;
                    continue;
                }

                if ('_map_collection' === $value_key) {
                    $mapCollection = $this->toCollection($value_value);

                    if ($mapCollection) {
                        $value_value = "[ $mapCollection ]";
                    } else {
                        $value_value = 'null';
                    }

                    $string_array[] = "'$key'";
                    $string_array[] = $value_value;
                    continue;
                }

                if (is_object($value_value)) {
                    throw new UnserializableException($value_key, 'Cannot Serialize Objects To String Please Convert To Array First');
                }

                if (is_string($value_value)) {
                    $value_value = json_encode($value_value, JSON_UNESCAPED_SLASHES);
                }

                if (is_bool($value_value)) {
                    $value_value = $value_value ? 'true' : 'false';
                }

                if (is_array($value_value)) {
                    throw new UnserializableException($key, 'It Is Currently Not Possible To Stringify Multidimensional Arrays, Pull Requests Are Welcome To Do So');
                }

                $string_array[] = "'$key'";
                $string_array[] = "$value_value";
            }
        }

        $string = $string_array ? implode(', ', $string_array) : null;

        return $string;
    }

    public function toPropertyString(array $array)
    {
        $string_array = array();

        foreach ($array as $key => $value) {
            if (is_object($value)) {
                throw new UnserializableException($key, 'Cannot Serialize Objects To String Please Convert To Array First');
            }

            if (is_string($value)) {
                $value = json_encode($value, JSON_UNESCAPED_SLASHES);
            }

            if (is_bool($value)) {
                $value = $value ? 'true' : 'false';
            }

            if (!is_array($value)) {
                $string_array[] = ".property('$key',$value)";
                continue;
            }

            foreach ((array) $value as $value_key => $value_value) {
                if ('_geoshapepoint' === $value_key) {
                    $lat = isset($value_value['lat']) ? $value_value['lat'] : null;
                    $lon = isset($value_value['lon']) ? $value_value['lon'] : null;

                    if ($lat && $lon) {
                        $value_value = "Geoshape.point($lat, $lon)";
                    } else {
                        $value_value = 'null';
                    }

                    $string_array[] = ".property('$key',$value_value)";
                    continue;
                }

                if ('_geoshapecircle' === $value_key) {
                    $lat = isset($value_value['lat']) ? $value_value['lat'] : null;
                    $lon = isset($value_value['lon']) ? $value_value['lon'] : null;
                    $radius = isset($value_value['radius']) ? $value_value['radius'] : null;
                    if ($lat && $lon && $radius) {
                        $value_value = "Geoshape.circle($lat, $lon, $radius)";
                    } else {
                        $value_value = 'null';
                    }

                    $string_array[] = ".property('$key',$value_value)";
                    continue;
                }

                if ('_geoshapebox' === $value_key) {
                    $sw_lat = isset($value_value['sw_lat']) ? $value_value['sw_lat'] : null;
                    $sw_lon = isset($value_value['sw_lon']) ? $value_value['sw_lon'] : null;
                    $ne_lat = isset($value_value['ne_lat']) ? $value_value['ne_lat'] : null;
                    $ne_lon = isset($value_value['ne_lon']) ? $value_value['ne_lon'] : null;

                    if ($sw_lat && $sw_lon && $ne_lat && $ne_lon) {
                        $value_value = "Geoshape.box($sw_lat, $sw_lon, $ne_lat, $ne_lon)";
                    } else {
                        $value_value = 'null';
                    }

                    $string_array[] = ".property('$key',$value_value)";
                    continue;
                }

                if ('_map_of' === $value_key) {
                    $map = $this->toString($value_value);

                    if ($map) {
                        $value_value = "Map.of($map)";
                    } else {
                        $value_value = 'null';
                    }

                    $string_array[] = "'$key'";
                    $string_array[] = $value_value;
                    continue;
                }

                if ('_list_collection' === $value_key) {
                    $listCollection = implode(', ', $value_value);

                    if ($listCollection) {
                        $value_value = "[ $listCollection ]";
                    } else {
                        $value_value = 'null';
                    }

                    $string_array[] = "'$key'";
                    $string_array[] = $value_value;
                    continue;
                }

                if ('_map_collection' === $value_key) {
                    $mapCollection = $this->toCollection($value_value);

                    if ($mapCollection) {
                        $value_value = "[ $mapCollection ]";
                    } else {
                        $value_value = 'null';
                    }

                    $string_array[] = "'$key'";
                    $string_array[] = $value_value;
                    continue;
                }

                if (is_object($value_value)) {
                    throw new UnserializableException($value_key, 'Cannot Serialize Objects To String Please Convert To Array First');
                }

                if (is_string($value_value)) {
                    $value_value = json_encode($value_value, JSON_UNESCAPED_SLASHES);
                }

                if (is_bool($value_value)) {
                    $value_value = $value_value ? 'true' : 'false';
                }

                if (is_array($value_value)) {
                    throw new UnserializableException($key, 'It Is Currently Not Possible To Stringify Multidimensional Arrays, Pull Requests Are Welcome To Do So');
                }

                $string_array[] = ".property('$key',$value_value)";
            }
        }

        $string = $string_array ? implode('', $string_array) : null;

        return $string;
    }


    public function toCollection(array $array)
    {
        $string_array = array();

        foreach ($array as $key => $value) {
            if (is_object($value)) {
                throw new UnserializableException($key, 'Cannot Serialize Objects To String Please Convert To Array First');
            }

            if (is_string($value)) {
                $value = json_encode($value, JSON_UNESCAPED_SLASHES);
            }

            if (is_bool($value)) {
                $value = $value ? 'true' : 'false';
            }

            if (!is_array($value)) {
                $string_array[] = "$key: $value";
                continue;
            }

            foreach ((array) $value as $value_key => $value_value) {
                if ('_geoshapepoint' === $value_key) {
                    $lat = isset($value_value['lat']) ? $value_value['lat'] : null;
                    $lon = isset($value_value['lon']) ? $value_value['lon'] : null;

                    if ($lat && $lon) {
                        $value_value = "Geoshape.point($lat, $lon)";
                    } else {
                        $value_value = 'null';
                    }

                    $string_array[] = "$key: $value_value";
                    continue;
                }

                if ('_geoshapecircle' === $value_key) {
                    $lat = isset($value_value['lat']) ? $value_value['lat'] : null;
                    $lon = isset($value_value['lon']) ? $value_value['lon'] : null;
                    $radius = isset($value_value['radius']) ? $value_value['radius'] : null;
                    if ($lat && $lon && $radius) {
                        $value_value = "Geoshape.circle($lat, $lon, $radius)";
                    } else {
                        $value_value = 'null';
                    }

                    $string_array[] = "$key: $value_value";
                    continue;
                }

                if ('_geoshapebox' === $value_key) {
                    $sw_lat = isset($value_value['sw_lat']) ? $value_value['sw_lat'] : null;
                    $sw_lon = isset($value_value['sw_lon']) ? $value_value['sw_lon'] : null;
                    $ne_lat = isset($value_value['ne_lat']) ? $value_value['ne_lat'] : null;
                    $ne_lon = isset($value_value['ne_lon']) ? $value_value['ne_lon'] : null;

                    if ($sw_lat && $sw_lon && $ne_lat && $ne_lon) {
                        $value_value = "Geoshape.box($sw_lat, $sw_lon, $ne_lat, $ne_lon)";
                    } else {
                        $value_value = 'null';
                    }

                    $string_array[] = "$key: $value_value";
                    continue;
                }

                if ('_map_of' === $value_key) {
                    $mapOf = $this->toString($value_value);

                    if ($mapOf) {
                        $value_value = "Map.of($mapOf)";
                    } else {
                        $value_value = 'null';
                    }

                    $string_array[] = "$key: $value_value";
                    continue;
                }

                if ('_list_collection' === $value_key) {
                    $listCollection = implode(', ', $value_value);

                    if ($listCollection) {
                        $value_value = "[ $listCollection ]";
                    } else {
                        $value_value = 'null';
                    }

                    $string_array[] = "'$key'";
                    $string_array[] = $value_value;
                    continue;
                }

                if ('_map_collection' === $value_key) {
                    $mapCollection = $this->toCollection($value_value);

                    if ($mapCollection) {
                        $value_value = "[ $mapCollection ]";
                    } else {
                        $value_value = 'null';
                    }

                    $string_array[] = "$key: $value_value";
                    continue;
                }

                if (is_object($value_value)) {
                    throw new UnserializableException($value_key, 'Cannot Serialize Objects To String Please Convert To Array First');
                }

                if (is_string($value_value)) {
                    $value_value = json_encode($value_value, JSON_UNESCAPED_SLASHES);
                }

                if (is_bool($value_value)) {
                    $value_value = $value_value ? 'true' : 'false';
                }

                if (is_array($value_value)) {
                    throw new UnserializableException($key, 'It Is Currently Not Possible To Stringify Multidimensional Arrays, Pull Requests Are Welcome To Do So');
                }

                $string_array[] = "'$key'";
                $string_array[] = "$value_value";
            }
        }

        $string = $string_array ? implode(', ', $string_array) : null;

        return $string;
    }

    public function toVertex(array $array)
    {
        $label = key($array);
        $vertex_properties = current($array);

        $string = $this->toString($vertex_properties);
        if ($string) {
            $command = "g.addV(label, '$label', $string);";
        } else {
            $command = "g.addV(label, '$label');";
        }

        return $command;
    }

    public function toEdge(string $edge_label, array $from_vertex, array $to_vertex, $object)
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

        if (true === $continue) {
            return;
        }

        if (is_string($from_vertex_value)) {
            $from_vertex_value = json_encode($from_vertex_value, JSON_UNESCAPED_SLASHES);
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

        if (true === $continue) {
            return;
        }

        if (is_string($to_vertex_value)) {
            $to_vertex_value = json_encode($to_vertex_value, JSON_UNESCAPED_SLASHES);
        }

        if (is_bool($to_vertex_value)) {
            $to_vertex_value = $to_vertex_value ? 'true' : 'false';
        }

        $properties = $this->toArray($object);
        $properties_string = $this->toString($properties);
        if ($properties_string) {
            $properties_string = ', '.$properties_string;
        }

        $add_edge_from_vertex = "g.V().hasLabel('$from_vertex_label').has('$from_vertex_key', $from_vertex_value)";
        $add_edge_to_vertex = "g.V().hasLabel('$to_vertex_label').has('$to_vertex_key', $to_vertex_value)";

        return "if ($add_edge_from_vertex.hasNext() == true && $add_edge_to_vertex.hasNext() == true) { $add_edge_from_vertex.next().addEdge('$edge_label', $add_edge_to_vertex.next()$properties_string) };";
    }
}
