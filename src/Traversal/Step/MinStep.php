<?php

namespace TheDonHimself\GremlinOGM\Traversal\Step;

use TheDonHimself\GremlinOGM\Traversal\BaseStep;

class MinStep extends BaseStep
{
    /**
     * @var string
     */
    protected $preSeparator = '.min(';
}
