<?php

namespace TheDonHimself\GremlinOGM\Traversal\Step;

use TheDonHimself\GremlinOGM\Traversal\BaseStep;

class GroupCountStep extends BaseStep
{
    /**
     * @var string
     */
    protected $preSeparator = '.groupCount(';
}
