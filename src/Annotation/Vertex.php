<?php

namespace TheDonHimself\GremlinOGM\Annotation;

/**
 * Annotation class for setting a classas Vertex.
 *
 * @author Don Omondi <don.e.omondi@gmail.com>
 * @Annotation
 * @Target("CLASS")
 */
class Vertex
{
    /**
     * @Required
     *
     * @var string
     */
    public $label;

    /**
     * @var array<\TheDonHimself\GremlinOGM\Annotation\Index>
     */
    public $indexes = array();
}
