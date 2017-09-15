<?php

namespace TheDonHimself\GremlinOGM\Annotation;

/**
 * Annotation class for setting a classas Vertex.
 *
 * Reference : http://docs.janusgraph.org/latest/indexes.html
 *
 * @author Don Omondi <don.e.omondi@gmail.com>
 * @Annotation
 * @Target({"ANNOTATION", "PROPERTY"})
 */
class AddEdge implements Annotation
{
    /**
     * @Required
     *
     * @var string
     */
    public $targetVertex;

    /**
     * @Required
     *
     * @var string
     */
    public $uniquePropertyKey;

    /**
     * @Required
     *
     * @var array
     */
    public $methodsForKeyValue = array();
}
