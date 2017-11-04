<?php

namespace TheDonHimself\GremlinOGM\Traversal\Step;

use TheDonHimself\GremlinOGM\Traversal\BaseStep;

class OrderStep extends BaseStep
{
    /**
     * @var string
     */
    protected $preSeparator = '.order(';
}
