<?php

namespace TheDonHimself\GremlinOGM\Traversal\Step\Predicates;

use TheDonHimself\GremlinOGM\Traversal\BaseStep;

class OutsidePredicate extends BaseStep
{
    /**
     * @var string
     */
    protected $preSeparator = '.outside(';
}
