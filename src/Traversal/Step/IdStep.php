<?php

namespace TheDonHimself\GremlinOGM\Traversal\Step;

use TheDonHimself\GremlinOGM\Traversal\BaseStep;

class IdStep extends BaseStep
{
    /**
     * @var string
     */
    protected $preSeparator = '.id(';
}
