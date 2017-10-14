<?php

namespace TheDonHimself\GremlinOGM\TwitterGraph\Graph\Edges;

use JMS\Serializer\Annotation as Serializer;
use TheDonHimself\GremlinOGM\Annotation as Graph;

/**
 *  @Serializer\ExclusionPolicy("all")
 *  @Graph\Edge(
 *      label="follows",
 *      multiplicity="MULTI"
 *  )
 */
class Follows
{
    /**
     *  @Graph\AddEdgeFromVertex(
     *      targetVertex="users",
     *      uniquePropertyKey="users_id",
     *      methodsForKeyValue={"getUserVertex1Id"}
     *  )
     */
    protected $userVertex1Id;

    /**
     *  @Graph\AddEdgeToVertex(
     *      targetVertex="users",
     *      uniquePropertyKey="users_id",
     *      methodsForKeyValue={"getUserVertex2Id"}
     *  )
     */
    protected $userVertex2Id;

    public function __construct($user1_vertex_id, $user2_vertex_id)
    {
        $this->userVertex1Id = $user1_vertex_id;
        $this->userVertex2Id = $user2_vertex_id;
    }

    /**
     * Get User 1 Vertex ID.
     *
     *
     * @return int
     */
    public function getUserVertex1Id()
    {
        return $this->userVertex1Id;
    }

    /**
     * Get User 2 Vertex ID.
     *
     *
     * @return int
     */
    public function getUserVertex2Id()
    {
        return $this->userVertex2Id;
    }
}
