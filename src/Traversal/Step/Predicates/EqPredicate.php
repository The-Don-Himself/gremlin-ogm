<?php

namespace TheDonHimself\GremlinOGM\Traversal\Step\Predicates;

use TheDonHimself\GremlinOGM\Traversal\BaseStep;

class EqPredicate extends BaseStep
{
    /**
     * @var string
     */
    protected $preSeparator = '.eq(';
}
