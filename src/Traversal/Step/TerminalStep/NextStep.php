<?php

namespace TheDonHimself\GremlinOGM\Traversal\Step\TerminalStep;

use TheDonHimself\GremlinOGM\Traversal\BaseStep;

class NextStep extends BaseStep
{
    /**
     * @var string
     */
    protected $preSeparator = '.next(';

}
