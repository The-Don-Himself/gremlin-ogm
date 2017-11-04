<?php

namespace TheDonHimself\GremlinOGM\Traversal\Step;

use TheDonHimself\GremlinOGM\Traversal\BaseStep;

class OptionalStep extends BaseStep
{
    /**
     * @var string
     */
    protected $preSeparator = '.optional(';
}
