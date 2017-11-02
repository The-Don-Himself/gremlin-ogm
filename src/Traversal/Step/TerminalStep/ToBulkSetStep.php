<?php

namespace TheDonHimself\GremlinOGM\Traversal\Step\TerminalStep;

use TheDonHimself\GremlinOGM\Traversal\BaseStep;

class ToBulkSetStep extends BaseStep
{
    /**
     * @var string
     */
    protected $preSeparator = '.toBulkSet(';

}
