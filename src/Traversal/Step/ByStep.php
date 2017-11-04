<?php

namespace TheDonHimself\GremlinOGM\Traversal\Step;

use TheDonHimself\GremlinOGM\Traversal\BaseStep;

class ByStep extends BaseStep
{
    /**
     * @var string
     */
    protected $preSeparator = '.by(';
}
