<?php

namespace TheDonHimself\GremlinOGM\Traversal\Step;

use TheDonHimself\GremlinOGM\Traversal\BaseStep;

class DropStep extends BaseStep
{
    /**
     * @var string
     */
    protected $preSeparator = '.drop(';

}
