<?php

namespace TheDonHimself\GremlinOGM\TwitterGraph\Graph\Edges;

use JMS\Serializer\Annotation as Serializer;
use TheDonHimself\GremlinOGM\Annotation as Graph;

/**
 *  @Serializer\ExclusionPolicy("all")
 *  @Graph\Edge(
 *      label="likes",
 *      multiplicity="MULTI"
 *  )
 */
class Likes
{
    /**
     *  @Graph\AddEdgeFromVertex(
     *      targetVertex="users",
     *      uniquePropertyKey="users_id",
     *      methodsForKeyValue={"getUserVertexId"}
     *  )
     */
    protected $userVertexId;

    /**
     *  @Graph\AddEdgeToVertex(
     *      targetVertex="tweets",
     *      uniquePropertyKey="tweets_id",
     *      methodsForKeyValue={"getTweetVertexId"}
     *  )
     */
    protected $tweetVertexId;

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
