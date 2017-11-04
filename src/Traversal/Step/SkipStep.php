<?php

namespace TheDonHimself\GremlinOGM\Traversal\Step;

use TheDonHimself\GremlinOGM\Traversal\BaseStep;

class SkipStep extends BaseStep
{
    /**
     * @var string
     */
    protected $preSeparator = '.skip(';
}
