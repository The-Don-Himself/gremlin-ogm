<?php

namespace TheDonHimself\GremlinOGM\Traversal\Step;

use TheDonHimself\GremlinOGM\Traversal\BaseStep;

class AddVertexStep extends BaseStep
{
    /**
     * @var string
     */
    protected $preSeparator = '.addVertex(';
}
