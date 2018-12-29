<?php

namespace TheDonHimself\GremlinOGM\Serializer\Handler;

use DateTime;
use JMS\Serializer\Context;
use JMS\Serializer\GraphNavigator;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\JsonSerializationVisitor;

class SerializerDateSubscriber implements SubscribingHandlerInterface
{
    public static function getSubscribingMethods()
    {
        return array(
            array(
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format' => 'json',
                'type' => 'DateTime',
                'method' => 'serializeDateTimeToString',
            ),
        );
    }

    public function serializeDateTimeToString(JsonSerializationVisitor $visitor, DateTime $date, array $type, Context $context)
    {
        return $date->getTimestamp() * 1000;
    }

}
