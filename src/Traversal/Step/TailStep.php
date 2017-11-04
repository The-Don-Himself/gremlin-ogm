<?php

namespace TheDonHimself\GremlinOGM\Traversal\Step;

use TheDonHimself\GremlinOGM\Traversal\BaseStep;

class TailStep extends BaseStep
{
    /**
     * @var string
     */
    protected $preSeparator = '.tail(';
}
