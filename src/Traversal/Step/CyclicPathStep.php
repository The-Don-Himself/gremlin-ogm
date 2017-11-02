<?php

namespace TheDonHimself\GremlinOGM\Traversal\Step;

use TheDonHimself\GremlinOGM\Traversal\BaseStep;

class CyclicPathStep extends BaseStep
{
    /**
     * @var string
     */
    protected $preSeparator = '.cyclicPath(';

}
