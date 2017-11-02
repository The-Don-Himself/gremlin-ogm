<?php

namespace TheDonHimself\GremlinOGM\Traversal\Step;

use TheDonHimself\GremlinOGM\Traversal\BaseStep;

class DedupStep extends BaseStep
{
    /**
     * @var string
     */
    protected $preSeparator = '.dedup(';

}
