<?php

namespace TheDonHimself\GremlinOGM\Traversal\Step;

use TheDonHimself\GremlinOGM\Traversal\BaseStep;

class WhereStep extends BaseStep
{
    /**
     * @var string
     */
    protected $preSeparator = '.where(';
}
