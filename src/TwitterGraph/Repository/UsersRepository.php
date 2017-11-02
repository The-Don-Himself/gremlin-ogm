<?php

namespace TheDonHimself\GremlinOGM\TwitterGraph\Repository;

use TheDonHimself\GremlinOGM\Repository\GraphRepository;
use TheDonHimself\GremlinOGM\Traversal\TraversalBuilder;
// use TheDonHimself\GremlinOGM\Traversal\Step\AggregateStep;

class UsersRepository extends GraphRepository
{
    public function queryUsers()
    {
/*
        $args = [0 => "'x'"];
        $aggregate = new AggregateStep($args);
        $aggregate_command = $aggregate->__toString();
        var_dump($aggregate_command);
        return;
*/

        $traversalBuilder = new TraversalBuilder();
        $command = $traversalBuilder
          ->g()
          ->V("'123'", 456, "'789'")
          ->aggregate('x')
          ->key('x')
          ->and(
            (new TraversalBuilder())->V("'123'", 456, "'789'")
          )
          ->getTraversal();
        var_dump($command);
        return $command;
    }

}
