<?php

namespace TheDonHimself\GremlinOGM\Traversal\Step;

use TheDonHimself\GremlinOGM\Traversal\BaseStep;

class LimitStep extends BaseStep
{
    /**
     * @var string
     */
    protected $preSeparator = '.limit(';

}
