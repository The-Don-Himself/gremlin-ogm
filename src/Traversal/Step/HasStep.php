<?php

namespace TheDonHimself\GremlinOGM\Traversal\Step;

use TheDonHimself\GremlinOGM\Traversal\BaseStep;

class HasStep extends BaseStep
{
    /**
     * @var string
     */
    protected $preSeparator = '.has(';

}
