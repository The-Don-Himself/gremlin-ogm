<?php

namespace TheDonHimself\GremlinOGM\TwitterGraph\Repository;

use TheDonHimself\GremlinOGM\Repository\GraphRepository;
use TheDonHimself\GremlinOGM\Traversal\TraversalBuilder;

class UsersRepository extends GraphRepository
{
    public function queryUserById($user_id)
    {
        $user_id = (int) $user_id;

        $traversalBuilder = new TraversalBuilder();

        $command = $traversalBuilder
          ->raw('def b = new Bindings(); ')
          ->raw('user_id = '.$user_id.'; ')

          ->g()
          ->V()
          ->hasLabel("'users'")
          ->has("'users_id'", "b.of('user_id', user_id)")
          ->getTraversal();

        return $command;
    }
}
