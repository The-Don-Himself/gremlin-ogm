<?php

namespace TheDonHimself\GremlinOGM\Traversal\Step;

use TheDonHimself\GremlinOGM\Traversal\BaseStep;

class ConstantStep extends BaseStep
{
    /**
     * @var string
     */
    protected $preSeparator = '.constant(';
}
