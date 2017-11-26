<?php

namespace AppBundle\GraphSearch;

use TheDonHimself\GremlinOGM\Traversal\TraversalBuilder;

class RetweetsSearch
{
    /**
     * @var TraversalBuilder
     */
    private $traversal;

    /**
     * @var array
     */
    private $traversals = array();

    /**
     * @var array
     */
    private $bindings = array();

    public function __construct(array $retweets)
    {
        $this->traversal = new TraversalBuilder();

        foreach ($retweets as $key => $value) {
            if ('retweeted_on' == $key) {  // The Twitter API doesn't provide this so it's just here for hypothetical reasons
            } elseif ('tweet' == $key && $value) {
                $search = new TweetsSearch($value);

                $traversals = $search->getTraversals() ?? array();
                $traversals ? $this->traversals = array_merge($this->traversals, $traversals) : null;

                $bindings = $search->getBindings() ?? array();
                $bindings ? $this->bindings = array_merge($this->bindings, $bindings) : null;
            }
        }
        $traversal = $this->traversal;
        $traversal->getTraversal() ? $this->traversals = array_merge($this->traversals, array('retweets' => '.'.$traversal)) : null;
    }

    /**
     * @return traversal
     */
    public function getTraversal()
    {
        $traversal = $this->traversal;

        return $traversal;
    }

    /**
     * @return traversals
     */
    public function getTraversals()
    {
        $traversals = $this->traversals;

        return $traversals;
    }

    /**
     * @return bindings
     */
    public function getBindings()
    {
        $bindings = $this->bindings;

        return $bindings;
    }
}
