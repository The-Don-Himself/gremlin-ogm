<?php

namespace TheDonHimself\GremlinOGM\Traversal\Step;

use TheDonHimself\GremlinOGM\Traversal\BaseStep;

class BarrierStep extends BaseStep
{
    /**
     * @var string
     */
    protected $preSeparator = '.barrier(';

}
