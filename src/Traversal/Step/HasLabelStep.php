<?php

namespace TheDonHimself\GremlinOGM\Traversal\Step;

use TheDonHimself\GremlinOGM\Traversal\BaseStep;

class HasLabelStep extends BaseStep
{
    /**
     * @var string
     */
    protected $preSeparator = '.hasLabel(';
}
