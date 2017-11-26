<?php

namespace TheDonHimself\GremlinOGM\TwitterGraph\Repository;

use TheDonHimself\GremlinOGM\Repository\GraphRepository;
use TheDonHimself\GremlinOGM\Traversal\TraversalBuilder;

class TweetsRepository extends GraphRepository
{
    public function queryTweetById($tweet_id)
    {
        $tweet_id = (int) $tweet_id;

        $traversalBuilder = new TraversalBuilder();

        $command = $traversalBuilder
          ->raw('b = new Bindings(); ')
          ->raw('tweet_id = '.$tweet_id.'; ')

          ->g()
          ->V()
          ->hasLabel("'tweets'")
          ->has("'tweets_id'", "b.of('tweet_id', tweet_id)")
          ->getTraversal();

        return $command;
    }

    public function getFeedForScreenName($screen_name)
    {
        $traversalBuilder = new TraversalBuilder();

        $command = $traversalBuilder
          ->raw('b = new Bindings(); ')
          ->raw("screen_name = textRegex('(?i)' + '".$screen_name."'); ")

          ->g()
          ->V()
          ->hasLabel("'users'")
          ->has("'screen_name'", "b.of('screen_name', screen_name)")
          ->union(
            (new TraversalBuilder())->out("'tweeted'")->getTraversal(),
            (new TraversalBuilder())->out("'follows'")->out("'tweeted'")->getTraversal()
          )
          ->order()
          ->by("'created_at'", 'decr')
          ->limit(10)
          ->getTraversal();

        return $command;
    }
}
