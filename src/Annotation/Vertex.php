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
     * Reference : http://docs.janusgraph.org/latest/advanced-schema.html#_static_vertices.
     *
     * @var bool
     */
    public $static = false;

    /**
     * Reference : http://docs.janusgraph.org/latest/advanced-schema.html#_vertex_ttl.
     *
     * @var string Duration.of TTL
     */
    public $ttl;

    /**
     * @var array<\TheDonHimself\GremlinOGM\Annotation\Index>
     */
    public $indexes = array();
}
