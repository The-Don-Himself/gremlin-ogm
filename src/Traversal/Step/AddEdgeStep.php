<?php

namespace TheDonHimself\GremlinOGM\Traversal\Step;

use TheDonHimself\GremlinOGM\Traversal\BaseStep;

class AddEdgeStep extends BaseStep
{
    /**
     * @var string
     */
    protected $preSeparator = '.addEdge(';
}
