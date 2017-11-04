<?php

namespace TheDonHimself\GremlinOGM\Traversal\Step;

use TheDonHimself\GremlinOGM\Traversal\BaseStep;

class SubgraphStep extends BaseStep
{
    /**
     * @var string
     */
    protected $preSeparator = '.subgraph(';
}
