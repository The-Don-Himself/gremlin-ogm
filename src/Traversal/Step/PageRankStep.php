<?php

namespace TheDonHimself\GremlinOGM\Traversal\Step;

use TheDonHimself\GremlinOGM\Traversal\BaseStep;

class PageRankStep extends BaseStep
{
    /**
     * @var string
     */
    protected $preSeparator = '.pageRank(';
}
