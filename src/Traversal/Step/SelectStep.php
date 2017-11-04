<?php

namespace TheDonHimself\GremlinOGM\Traversal\Step;

use TheDonHimself\GremlinOGM\Traversal\BaseStep;

class SelectStep extends BaseStep
{
    /**
     * @var string
     */
    protected $preSeparator = '.select(';
}
