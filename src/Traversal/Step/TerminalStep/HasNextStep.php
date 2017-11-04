<?php

namespace TheDonHimself\GremlinOGM\Traversal\Step\TerminalStep;

use TheDonHimself\GremlinOGM\Traversal\BaseStep;

class HasNextStep extends BaseStep
{
    /**
     * @var string
     */
    protected $preSeparator = '.hasNext(';
}
