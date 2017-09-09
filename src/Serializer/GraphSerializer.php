<?php

namespace TheDonHimself\GremlinOGM\Serializer;

use TheDonHimself\GremlinOGM\Serializer\Handler\SerializerDateSubscriber;
use TheDonHimself\GremlinOGM\Exception\UnserializableException;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\Handler\HandlerRegistry;
use JMS\Serializer\EventDispatcher\EventDispatcher;

class GraphSerializer
{
    public function getSerializer()
    {
        $serializer = SerializerBuilder::create()
            ->addDefaultHandlers()
            ->configureHandlers(function(HandlerRegistry $registry) {
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
            if(is_object($value)){
                throw new UnserializableException($key, 'Cannot Serialize Objects To String Please Convert To Array First');
            }

            if(is_string($value)){
                $value = trim(preg_replace('/\s+/', ' ', $value));
                //  $value = trim(preg_replace('/\s\s+/', ' ', $value));
                $value = addslashes($value);
                $value = "'$value'";
            }

            if(is_bool($value)){
                $value = $value ? "true" : "false";
            }

            if(!is_array($value)){
                $string_array[] = "'$key'";
                $string_array[] = "$value";
                continue;
            }

            foreach ((array)$value as $value_key => $value_value) {
                if($value_key === '_geoshapepoint'){
                    $lat = isset($value_value['lat']) ? $value_value['lat'] : null;
                    $lon = isset($value_value['lon']) ? $value_value['lon'] : null;

                    if($lat && $lon){
                        $value_value = "Geoshape.point($lat, $lon)";
                    } else {
                        $value_value = "null";
                    }

                    $string_array[] = "'$key'";
                    $string_array[] = $value_value;
                    continue;
                }

                if($value_key === '_geoshapecircle'){
                    $lat = isset($value_value['lat']) ? $value_value['lat'] : null;
                    $lon = isset($value_value['lon']) ? $value_value['lon'] : null;
                    $radius = isset($value_value['radius']) ? $value_value['radius'] : null;
                    if($lat && $lon && $radius){
                        $value_value = "Geoshape.circle($lat, $lon, $radius)";
                    } else {
                        $value_value = "null";
                    }

                    $string_array[] = "'$key'";
                    $string_array[] = $value_value;
                    continue;
                }

                if($value_key === '_geoshapebox'){
                    $sw_lat = isset($value_value['sw_lat']) ? $value_value['sw_lat'] : null;
                    $sw_lon = isset($value_value['sw_lon']) ? $value_value['sw_lon'] : null;
                    $ne_lat = isset($value_value['ne_lat']) ? $value_value['ne_lat'] : null;
                    $ne_lon = isset($value_value['ne_lon']) ? $value_value['ne_lon'] : null;

                    if($sw_lat && $sw_lon && $ne_lat && $ne_lon){
                        $value_value = "Geoshape.box($sw_lat, $sw_lon, $ne_lat, $ne_lon)";
                    } else {
                        $value_value = "null";
                    }

                    $string_array[] = "'$key'";
                    $string_array[] = $value_value;
                    continue;
                }

                if(is_object($value_value)){
                    throw new UnserializableException($value_key, 'Cannot Serialize Objects To String Please Convert To Array First');
                }

                if(is_string($value_value)){
                    $value_value = trim(preg_replace('/\s+/', ' ', $value_value));
                    $value_value = addslashes($value_value);
                    $value_value = "'$value_value'";
                }

                if(is_bool($value_value)){
                    $value_value = $value_value ? "true" : "false";
                }

                if(is_array($value_value)){
                    throw new UnserializableException($key, 'It Is Currently Not Possible To Stringify Multidimensional Arrays, Pull Requests Are Welcome To Do So');
                }

                $string_array[] = "'$key'";
                $string_array[] = "$value_value";
            }
        }

        $string = implode(', ', $string_array);

        return $string;
    }

}
