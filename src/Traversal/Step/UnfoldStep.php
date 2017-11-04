<?php

namespace TheDonHimself\GremlinOGM\Traversal\Step;

use TheDonHimself\GremlinOGM\Traversal\BaseStep;

class UnfoldStep extends BaseStep
{
    /**
     * @var string
     */
    protected $preSeparator = '.unfold(';
}
