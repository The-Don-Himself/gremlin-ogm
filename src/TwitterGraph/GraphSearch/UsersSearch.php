<?php

namespace TheDonHimself\GremlinOGM\TwitterGraph\GraphSearch;

use TheDonHimself\GremlinOGM\GraphSearch\SearchPredicates;
use TheDonHimself\GremlinOGM\Traversal\TraversalBuilder;

class UsersSearch
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

    public function __construct(array $user)
    {
        $this->traversal = new TraversalBuilder();

        foreach ($user as $key => $value) {
            if ('id' == $key && $value) {
                $searchPredicate = new SearchPredicates($value, 'integer');
                $predicate = $searchPredicate->getPredicate();

                $binding_key = 'user_id';

                if (
                    'inside' === $predicate ||
                    'outside' === $predicate ||
                    'between' === $predicate
                ) {
                    $param1 = $searchPredicate->getParam1();
                    $param2 = $searchPredicate->getParam2();

                    $binding_value = $predicate.'('.$param1.', '.$param2.')';
                } else {
                    $binding_value = $predicate.'('.$searchPredicate->getParams().')';
                }

                $search = $this->traversal;
                $search->has("'$key'", "b.of('$binding_key', $binding_key)");

                $this->traversal = $search;

                $bindings = array($binding_key => $binding_value);
                $bindings ? $this->bindings = array_merge($this->bindings, $bindings) : null;
            } elseif ('name' == $key && $value) {
                $searchPredicate = new SearchPredicates($value, 'string');
                $predicate = $searchPredicate->getPredicate();

                $binding_key = 'user_name';

                if (
                    'inside' === $predicate ||
                    'outside' === $predicate ||
                    'between' === $predicate
                ) {
                    $param1 = $searchPredicate->getParam1();
                    $param2 = $searchPredicate->getParam2();

                    $binding_value = $predicate.'('.$param1.', '.$param2.')';
                } else {
                    $binding_value = $predicate.'('.$searchPredicate->getParams().')';
                }

                $search = $this->traversal;
                $search->has("'$key'", "b.of('$binding_key', $binding_key)");

                $this->traversal = $search;

                $bindings = array($binding_key => $binding_value);
                $bindings ? $this->bindings = array_merge($this->bindings, $bindings) : null;
            } elseif ('screen_name' == $key && $value) {
                $searchPredicate = new SearchPredicates($value, 'string');
                $predicate = $searchPredicate->getPredicate();

                $binding_key = 'user_screen_name';

                if (
                    'inside' === $predicate ||
                    'outside' === $predicate ||
                    'between' === $predicate
                ) {
                    $param1 = $searchPredicate->getParam1();
                    $param2 = $searchPredicate->getParam2();

                    $binding_value = $predicate.'('.$param1.', '.$param2.')';
                } else {
                    $binding_value = $predicate.'('.$searchPredicate->getParams().')';
                }

                $search = $this->traversal;
                $search->has("'$key'", "b.of('$binding_key', $binding_key)");

                $this->traversal = $search;

                $bindings = array($binding_key => $binding_value);
                $bindings ? $this->bindings = array_merge($this->bindings, $bindings) : null;
            } elseif ('status' == $key && $value) {
                $search = new TweetsSearch($value);

                $traversals = $search->getTraversals() ?? array();
                $traversals ? $this->traversals = array_merge($this->traversals, $traversals) : null;

                $bindings = $search->getBindings() ?? array();
                $bindings ? $this->bindings = array_merge($this->bindings, $bindings) : null;
            }
        }
        $traversal = $this->traversal;
        $traversal->getTraversal() ? $this->traversals = array_merge($this->traversals, array('user' => '.'.$traversal)) : null;
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

    /**
     * @return TraversalBuilder
     */
    public function queryUsers()
    {
        $traversals = $this->traversals;
        $bindings = $this->bindings;

        $traversalBuilder = new TraversalBuilder();

        $traversalBuilder
          ->raw('def b = new Bindings(); ');

        foreach ($bindings as $binding_key => $binding_value) {
            $traversalBuilder->raw($binding_key.' = '.$binding_value.'; ');
        }

        // Default Bindings Example
        // $traversalBuilder->raw('user_deleted = false; ');

        $traversalBuilder
          ->g()
          ->V();

        if (isset($traversals['tweet'])) {
            $traversalBuilder
                ->hasLabel("'tweets'")
                ->raw($traversals['tweet'])
                ->in("'tweeted'");
        }

        $traversalBuilder
            ->hasLabel("'users'");

        isset($traversals['user']) ? $traversalBuilder->raw($traversals['user']) : null;

        return $traversalBuilder;
    }

    /**
     * @return TraversalBuilder
     */
    public function filterFields(TraversalBuilder $traversal, array $fields)
    {
        $property_keys = array();

        foreach ($fields as $field) {
            if ('id' == $field) {
                $property_keys[] = "'users_id'";
            } elseif ('name' == $field) {
                $property_keys[] = "'name'";
            } elseif ('screen_name' == $field) {
                $property_keys[] = "'screen_name'";
                // } elseif ('profile_image_url_https' == $field) { TO-DO Implement other fields
            //   $property_keys[] = "'profile_image_url_https'";
            }
        }

        $valueMap = $property_keys ? implode(', ', $property_keys) : null;

        $valueMap ? $traversal->valueMap($valueMap) : null;

        return $traversal;
    }
}
