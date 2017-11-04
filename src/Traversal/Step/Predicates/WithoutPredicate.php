<?php

namespace TheDonHimself\GremlinOGM\Traversal\Step\Predicates;

use TheDonHimself\GremlinOGM\Traversal\BaseStep;

class WithoutPredicate extends BaseStep
{
    /**
     * @var string
     */
    protected $preSeparator = '.without(';
}
