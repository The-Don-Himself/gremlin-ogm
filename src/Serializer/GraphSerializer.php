<?php

namespace TheDonHimself\GremlinOGM\Serializer;

use TheDonHimself\GremlinOGM\Serializer\Handler\SerializerDateSubscriber;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\Handler\HandlerRegistry;
use JMS\Serializer\EventDispatcher\EventDispatcher;

class GraphSerializer
{
    public function toArray($object)
    {
        $serializer = SerializerBuilder::create()
            ->addDefaultHandlers()
            ->configureHandlers(function(HandlerRegistry $registry) {
                $registry->registerSubscribingHandler(new SerializerDateSubscriber());
            })
            ->build();

        $array = $serializer->toArray($object, SerializationContext::create()->setGroups(array('Graph')));

        return $array;
    }

}
