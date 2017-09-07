<?php

namespace TheDonHimself\GremlinOGM\Annotation;

/**
 * Annotation class for setting a classas Vertex.
 *
 * @author Don Omondi <don.e.omondi@gmail.com>
 * @Annotation
 * @Target({"PROPERTY","METHOD", "ANNOTATION"})
 */
class PropertyCardinality
{
    /**
     * Reference : http://docs.janusgraph.org/latest/schema.html.
     *
     * @Required
     *
     * @var string
     * @Enum({"SINGLE", "LIST", "SET"})
     */
    public $name;
}
