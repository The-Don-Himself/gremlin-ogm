<?php

namespace TheDonHimself\GremlinOGM\Traversal\Step;

use TheDonHimself\GremlinOGM\Traversal\BaseStep;

class HasValueStep extends BaseStep
{
    /**
     * @var string
     */
    protected $preSeparator = '.hasValue(';

}
