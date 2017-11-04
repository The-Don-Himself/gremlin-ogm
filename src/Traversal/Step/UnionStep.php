<?php

namespace TheDonHimself\GremlinOGM\Traversal\Step;

use TheDonHimself\GremlinOGM\Traversal\BaseStep;

class UnionStep extends BaseStep
{
    /**
     * @var string
     */
    protected $preSeparator = '.union(';
}
