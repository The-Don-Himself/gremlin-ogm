<?php

namespace TheDonHimself\GremlinOGM\Traversal\Step;

use TheDonHimself\GremlinOGM\Traversal\BaseStep;

class CountStep extends BaseStep
{
    /**
     * @var string
     */
    protected $preSeparator = '.count(';
}
