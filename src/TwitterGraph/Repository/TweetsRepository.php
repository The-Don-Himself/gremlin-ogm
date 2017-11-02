<?php

namespace TheDonHimself\GremlinOGM\TwitterGraph\Repository;

use TheDonHimself\GremlinOGM\Repository\GraphRepository;
use TheDonHimself\GremlinOGM\Traversal\TraversalBuilder;

class TweetsRepository extends GraphRepository
{
    public function getTimelineForScreenName($screen_name)
    {
        $traversalBuilder = new TraversalBuilder();
        $command = $traversalBuilder
          ->g()
          ->V()
          ->hasLabel("'users'")
          ->has("'screen_name'", "textRegex('(?i)the_don_himself')")
          ->union(
            (new TraversalBuilder())->out("'tweeted'")->getTraversal(),
            (new TraversalBuilder())->out("'follows'")->out("'tweeted'")->getTraversal()
          )
          ->order()->by("'created'", "decr")
          ->limit(10)
          ->getTraversal();
        var_dump($command);
        return $command;
    }

}
