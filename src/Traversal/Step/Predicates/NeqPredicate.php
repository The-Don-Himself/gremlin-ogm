<?php

namespace TheDonHimself\GremlinOGM\Traversal\Step\Predicates;

use TheDonHimself\GremlinOGM\Traversal\BaseStep;

class NeqPredicate extends BaseStep
{
    /**
     * @var string
     */
    protected $preSeparator = '.neq(';
}
