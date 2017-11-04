<?php

namespace TheDonHimself\GremlinOGM\Traversal\Step;

use TheDonHimself\GremlinOGM\Traversal\BaseStep;

class InjectStep extends BaseStep
{
    /**
     * @var string
     */
    protected $preSeparator = '.inject(';
}
