<?php

namespace TheDonHimself\GremlinOGM\Traversal\Step;

use TheDonHimself\GremlinOGM\Traversal\BaseStep;

class HasIdStep extends BaseStep
{
    /**
     * @var string
     */
    protected $preSeparator = '.hasId(';

}
