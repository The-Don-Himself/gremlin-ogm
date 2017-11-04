<?php

namespace TheDonHimself\GremlinOGM\Annotation;

/**
 * Annotation class for setting a classas Edge.
 *
 * @author Don Omondi <don.e.omondi@gmail.com>
 * @Annotation
 * @Target("CLASS")
 */
class Edge
{
    /**
     * @Required
     *
     * @var string
     */
    public $label;

    /**
     * Reference : http://docs.janusgraph.org/latest/schema.html.
     *
     * @Required
     *
     * @var string
     * @Enum({"MULTI", "SIMPLE", "MANY2ONE", "ONE2MANY", "ONE2ONE"})
     */
    public $multiplicity;

    /**
     * Reference : http://docs.janusgraph.org/latest/advanced-schema.html#_unidirected_edges.
     *
     * @var boolean
     */
    public $unidirected = false;

    /**
     * Reference : http://docs.janusgraph.org/latest/advanced-schema.html#_edge_ttl.
     *
     * @var string Duration.of TTL
     */
    public $ttl;

    /**
     * @var array<\TheDonHimself\GremlinOGM\Annotation\Index>
     */
    public $indexes = array();
}
