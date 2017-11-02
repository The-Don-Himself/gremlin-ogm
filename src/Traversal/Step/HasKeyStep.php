<?php

namespace TheDonHimself\GremlinOGM\Traversal\Step;

use TheDonHimself\GremlinOGM\Traversal\BaseStep;

class HasKeyStep extends BaseStep
{
    /**
     * @var string
     */
    protected $preSeparator = '.hasKey(';

}
