<?php

namespace TheDonHimself\GremlinOGM\Traversal\Step;

use TheDonHimself\GremlinOGM\Traversal\BaseStep;

class NotStep extends BaseStep
{
    /**
     * @var string
     */
    protected $preSeparator = '.not(';
}
