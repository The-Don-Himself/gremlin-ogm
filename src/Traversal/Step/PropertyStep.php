<?php

namespace TheDonHimself\GremlinOGM\Traversal\Step;

use TheDonHimself\GremlinOGM\Traversal\BaseStep;

class PropertyStep extends BaseStep
{
    /**
     * @var string
     */
    protected $preSeparator = '.property(';
}
