<?php

namespace TheDonHimself\GremlinOGM\Traversal\Step\Predicates;

use TheDonHimself\GremlinOGM\Traversal\BaseStep;

class BetweenPredicate extends BaseStep
{
    /**
     * @var string
     */
    protected $preSeparator = '.between(';

}
