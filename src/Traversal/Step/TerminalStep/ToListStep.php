<?php

namespace TheDonHimself\GremlinOGM\Traversal\Step\TerminalStep;

use TheDonHimself\GremlinOGM\Traversal\BaseStep;

class ToListStep extends BaseStep
{
    /**
     * @var string
     */
    protected $preSeparator = '.toList(';

}
