<?php

namespace TheDonHimself\GremlinOGM\Traversal\Step\Predicates;

use TheDonHimself\GremlinOGM\Traversal\BaseStep;

class InsidePredicate extends BaseStep
{
    /**
     * @var string
     */
    protected $preSeparator = '.inside(';
}
