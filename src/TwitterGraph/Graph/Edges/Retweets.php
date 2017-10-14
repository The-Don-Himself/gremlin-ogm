<?php

namespace TheDonHimself\GremlinOGM\TwitterGraph\Graph\Edges;

use JMS\Serializer\Annotation as Serializer;
use TheDonHimself\GremlinOGM\Annotation as Graph;

/**
 *  @Serializer\ExclusionPolicy("all")
 *  @Graph\Edge(
 *      label="retweets",
 *      multiplicity="ONE2MANY"
 *  )
 */
class Retweets
{
    /**
     *  @Graph\AddEdgeFromVertex(
     *      targetVertex="tweets",
     *      uniquePropertyKey="tweets_id",
     *      methodsForKeyValue={"getTweetVertex1Id"}
     *  )
     */
    protected $tweetVertex1Id;

    /**
     *  @Graph\AddEdgeToVertex(
     *      targetVertex="tweets",
     *      uniquePropertyKey="tweets_id",
     *      methodsForKeyValue={"getTweetVertex2Id"}
     *  )
     */
    protected $tweetVertex2Id;

    public function __construct($tweet1_vertex_id, $tweet2_vertex_id)
    {
        $this->tweetVertex1Id = $tweet1_vertex_id;
        $this->tweetVertex2Id = $tweet2_vertex_id;
    }

    /**
     * Get Tweet 1 Vertex ID.
     *
     *
     * @return int
     */
    public function getTweetVertex1Id()
    {
        return $this->tweetVertex1Id;
    }

    /**
     * Get Tweet 2 Vertex ID.
     *
     *
     * @return int
     */
    public function getTweetVertex2Id()
    {
        return $this->tweetVertex2Id;
    }
}
