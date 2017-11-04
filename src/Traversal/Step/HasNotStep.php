<?php

namespace TheDonHimself\GremlinOGM\Traversal\Step;

use TheDonHimself\GremlinOGM\Traversal\BaseStep;

class HasNotStep extends BaseStep
{
    /**
     * @var string
     */
    protected $preSeparator = '.hasNot(';
}
