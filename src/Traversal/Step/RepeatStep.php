<?php

namespace TheDonHimself\GremlinOGM\Traversal\Step;

use TheDonHimself\GremlinOGM\Traversal\BaseStep;

class RepeatStep extends BaseStep
{
    /**
     * @var string
     */
    protected $preSeparator = '.repeat(';
}
