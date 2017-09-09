<?php

namespace TheDonHimself\GremlinOGM\Serializer;

use TheDonHimself\GremlinOGM\Serializer\Handler\SerializerDateSubscriber;
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

        foreach ($array as $key => &$value) {
            if(is_string($value)){
                $value = trim(preg_replace('/\s+/', ' ', $value));
//                $value = trim(preg_replace('/\s\s+/', ' ', $value));
                $value = addslashes($value);
                $value = "'$value'";
            }
            if(is_array($value)){
                $value = $this->toString($value);
                $value = "[" . $value . "]";
            }
            if(is_object($value)){
                $value = $this->toString($value);
                $value = "[" . $value . "]";
            }
            if(!is_integer($key)){
                $string_array[] = "'$key'";
            }
            $string_array[] = "$value";
        }

        $string = implode(', ', $string_array);

        return $string;
    }

}
