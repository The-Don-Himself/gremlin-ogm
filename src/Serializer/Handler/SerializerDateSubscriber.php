<?php

namespace TheDonHimself\GremlinOGM\Serializer\Handler;

use DateTime;
use DateTimeZone;
use JMS\Serializer\Context;
use JMS\Serializer\GraphNavigator;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\JsonSerializationVisitor;

class SerializerDateSubscriber implements SubscribingHandlerInterface
{
    private $defaultFormat;
    private $defaultTimezone;
    private $xmlCData;

    public function __construct($defaultFormat = DateTime::ISO8601, $defaultTimezone = 'UTC', $xmlCData = true)
    {
        $this->defaultFormat = $defaultFormat;
        $this->defaultTimezone = new DateTimeZone($defaultTimezone);
        $this->xmlCData = $xmlCData;
    }

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
        if ($this->isInGroups(['Graph'], $context)) {
            return $date->getTimestamp() * 1000;
        }

        return $visitor->visitString($date->format($this->getFormat($type)), $type, $context);
    }

    private function isInGroups(array $validGroup, Context $context)
    {
        $option = $context->attributes->get('groups')
                ->filter(function ($groups) use ($validGroup) {
                    return sizeof(array_intersect($groups, $validGroup)) > 0;
                });

        return !$option->isEmpty();
    }

    /**
     * @param array $type
     *
     * @return string
     */
    private function getFormat(array $type)
    {
        return isset($type['params'][0]) ? $type['params'][0] : $this->defaultFormat;
    }
}
