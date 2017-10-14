<?php

namespace TheDonHimself\GremlinOGM\TwitterGraph\Graph\Edges;

use JMS\Serializer\Annotation as Serializer;
use TheDonHimself\GremlinOGM\Annotation as Graph;

/**
 *  Note : We name this edge Tweeted because we already have a Vertex by the name Tweets.
 *
 *  @Serializer\ExclusionPolicy("all")
 *  @Graph\Edge(
 *      label="tweeted",
 *      multiplicity="ONE2MANY"
 *  )
 */
class Tweeted
{
    /**
     *  @Graph\AddEdgeFromVertex(
     *      targetVertex="users",
     *      uniquePropertyKey="users_id",
     *      methodsForKeyValue={"getUserVertexId"}
     *  )
     */
    public $userVertexId;

    /**
     *  @Graph\AddEdgeToVertex(
     *      targetVertex="tweets",
     *      uniquePropertyKey="tweets_id",
     *      methodsForKeyValue={"getTweetVertexId"}
     *  )
     */
    public $tweetVertexId;

    public function __construct($userVertexId, $tweetVertexId)
    {
        $this->userVertexId = $userVertexId;
        $this->tweetVertexId = $tweetVertexId;
    }

    /**
     * Get User Vertex ID.
     *
     *
     * @return int
     */
    public function getUserVertexId()
    {
        return $this->userVertexId;
    }

    /**
     * Get Tweet Vertex ID.
     *
     *
     * @return int
     */
    public function getTweetVertexId()
    {
        return $this->tweetVertexId;
    }
}
