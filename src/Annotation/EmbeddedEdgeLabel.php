<?php

namespace TheDonHimself\GremlinOGM\Annotation;

/**
 * Annotation class for setting a classas Edge.
 *
 * @author Don Omondi <don.e.omondi@gmail.com>
 * @Annotation
 * @Target("ANNOTATION")
 */
class EmbeddedEdgeLabel implements Annotation
{
    /**
     * @Required
     *
     * @var string
     */
    public $label;
}
