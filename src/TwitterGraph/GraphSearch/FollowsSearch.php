<?php

namespace AppBundle\GraphSearch;

use TheDonHimself\GremlinOGM\Traversal\TraversalBuilder;

class FollowSearch
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

    public function __construct(array $follows)
    {
        $this->traversal = new TraversalBuilder();

        foreach ($follows as $key => $value) {
            if ('followed_on' == $key) {  // The Twitter API doesn't provide this so it's just here for hypothetical reasons
            } elseif ('user' == $key && $value) {
                $search = new UsersSearch($value);

                $traversals = $search->getTraversals() ?? array();
                $traversals ? $this->traversals = array_merge($this->traversals, $traversals) : null;

                $bindings = $search->getBindings() ?? array();
                $bindings ? $this->bindings = array_merge($this->bindings, $bindings) : null;
            }
        }
        $traversal = $this->traversal;
        $traversal->getTraversal() ? $this->traversals = array_merge($this->traversals, array('follows' => '.'.$traversal)) : null;
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
