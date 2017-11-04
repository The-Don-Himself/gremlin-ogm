<?php

namespace TheDonHimself\GremlinOGM\Traversal\Step;

use TheDonHimself\GremlinOGM\Traversal\BaseStep;

class MaxStep extends BaseStep
{
    /**
     * @var string
     */
    protected $preSeparator = '.max(';
}
