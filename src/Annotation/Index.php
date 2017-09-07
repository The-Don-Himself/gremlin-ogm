<?php

namespace TheDonHimself\GremlinOGM\Annotation;

/**
 * Annotation class for setting a classas Vertex.
 *
 * Reference : http://docs.janusgraph.org/latest/indexes.html
 *
 * @author Don Omondi <don.e.omondi@gmail.com>
 * @Annotation
 * @Target("ANNOTATION")
 */
class Index implements Annotation
{
    /**
     * @Required
     *
     * @var string
     */
    public $name;

    /**
     * @Required
     *
     * @var string
     * @Enum({"Composite", "Mixed", "Vertex-centric"})
     */
    public $type;

    /**
     * NOTE: Unique indexes can only be created on vertices
     *
     * @var bool
     */
    public $unique = false;

    /**
     * @var bool
     */
    public $label_constraint = false;

    /**
     * @var string
     * @Enum({"IN", "OUT", "BOTH"})
     */
    public $direction = 'BOTH';

    /**
     * @var string
     * @Enum({"asc", "decr"})
     */
    public $order = 'asc';

    /**
     * Mixed Index Reference : http://docs.janusgraph.org/latest/search-predicates.html#mixeddatatypes
     * Supported Types For Mixed Index : Byte Short Integer Long Float Double Decimal Precision String Geoshape Date Instant
     * Supported Mapping For Mixed Index : DEFAULT, TEXT, STRING, TEXTSTRING, PREFIX_TREE;.
     *
     * @Required
     *
     * @var array
     */
    public $keys = array();
}
