<?php

namespace TheDonHimself\GremlinOGM\Traversal\Step\TerminalStep;

use TheDonHimself\GremlinOGM\Traversal\BaseStep;

class ToSetStep extends BaseStep
{
    /**
     * @var string
     */
    protected $preSeparator = '.toSet(';
}
