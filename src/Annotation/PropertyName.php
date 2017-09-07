<?php

namespace TheDonHimself\GremlinOGM\Annotation;

/**
 * Annotation class for setting a classas Vertex.
 *
 * @author Don Omondi <don.e.omondi@gmail.com>
 * @Annotation
 * @Target({"PROPERTY","METHOD", "ANNOTATION"})
 */
class PropertyName
{
    /**
     * @Required
     *
     * @var string
     */
    public $name;
}
