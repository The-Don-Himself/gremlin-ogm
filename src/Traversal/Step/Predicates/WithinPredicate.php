<?php

namespace TheDonHimself\GremlinOGM\Traversal\Step\Predicates;

use TheDonHimself\GremlinOGM\Traversal\BaseStep;

class WithinPredicate extends BaseStep
{
    /**
     * @var string
     */
    protected $preSeparator = '.within(';
}
