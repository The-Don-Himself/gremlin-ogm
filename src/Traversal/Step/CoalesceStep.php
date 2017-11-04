<?php

namespace TheDonHimself\GremlinOGM\Traversal\Step;

use TheDonHimself\GremlinOGM\Traversal\BaseStep;

class CoalesceStep extends BaseStep
{
    /**
     * @var string
     */
    protected $preSeparator = '.coalesce(';
}
