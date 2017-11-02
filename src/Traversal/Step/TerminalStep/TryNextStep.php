<?php

namespace TheDonHimself\GremlinOGM\Traversal\Step\TerminalStep;

use TheDonHimself\GremlinOGM\Traversal\BaseStep;

class TryNextStep extends BaseStep
{
    /**
     * @var string
     */
    protected $preSeparator = '.tryNext(';

}
