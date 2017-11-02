<?php

namespace TheDonHimself\GremlinOGM\Traversal\Step;

use TheDonHimself\GremlinOGM\Traversal\BaseStep;

class KeyStep extends BaseStep
{
    /**
     * @var string
     */
    protected $preSeparator = '.key(';

}
