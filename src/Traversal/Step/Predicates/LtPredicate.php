<?php

namespace TheDonHimself\GremlinOGM\Traversal\Step\Predicates;

use TheDonHimself\GremlinOGM\Traversal\BaseStep;

class LtPredicate extends BaseStep
{
    /**
     * @var string
     */
    protected $preSeparator = '.lt(';
}
