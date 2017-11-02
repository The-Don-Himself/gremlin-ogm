<?php

namespace TheDonHimself\GremlinOGM\Traversal\Step;

use TheDonHimself\GremlinOGM\Traversal\BaseStep;

class TimeLimitStep extends BaseStep
{
    /**
     * @var string
     */
    protected $preSeparator = '.timeLimit(';

}
