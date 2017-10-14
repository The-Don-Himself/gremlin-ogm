<?php

namespace TheDonHimself\GremlinOGM\TwitterGraph\Graph\Vertices;

use JMS\Serializer\Annotation as Serializer;
use TheDonHimself\GremlinOGM\Annotation as Graph;

/**
 *  @Serializer\ExclusionPolicy("all")
 *  @Graph\Vertex(
 *      label="tweets",
 *      indexes={
 *          @Graph\Index(
 *              name="byTweetsIdComposite",
 *              type="Composite",
 *              unique=true,
 *              label_constraint=true,
 *              keys={
 *                  "tweets_id"
 *              }
 *          ),
 *          @Graph\Index(
 *              name="tweetsMixed",
 *              type="Mixed",
 *              label_constraint=true,
 *              keys={
 *                  "tweets_id"       : "DEFAULT",
 *                  "text"            : "TEXT",
 *                  "retweet_count"   : "DEFAULT",
 *                  "created_at"      : "DEFAULT",
 *                  "favorited"       : "DEFAULT",
 *                  "retweeted"       : "DEFAULT",
 *                  "source"          : "STRING"
 *              }
 *          )
 *      }
 *  )
 */
class Tweets
{
    /**
     * @Serializer\Type("integer")
     * @Serializer\Expose
     * @Serializer\Groups({"Default"})
     */
    public $id;

    /**
     * @Serializer\VirtualProperty
     * @Serializer\Expose
     * @Serializer\Type("integer")
     * @Serializer\Groups({"Graph"})
     * @Serializer\SerializedName("tweets_id")
     * @Graph\Id
     * @Graph\PropertyName("tweets_id")
     * @Graph\PropertyType("Long")
     * @Graph\PropertyCardinality("SINGLE")
     */
    public function getVirtualId()
    {
        return self::getId();
    }

    /**
     * @Serializer\Type("string")
     * @Serializer\Expose
     * @Serializer\Groups({"Default", "Graph"})
     * @Graph\PropertyName("text")
     * @Graph\PropertyType("String")
     * @Graph\PropertyCardinality("SINGLE")
     */
    public $text;

    /**
     * @Serializer\Type("integer")
     * @Serializer\Expose
     * @Serializer\Groups({"Default", "Graph"})
     * @Graph\PropertyName("retweet_count")
     * @Graph\PropertyType("Integer")
     * @Graph\PropertyCardinality("SINGLE")
     */
    public $retweet_count;

    /**
     * @Serializer\Type("boolean")
     * @Serializer\Expose
     * @Serializer\Groups({"Default", "Graph"})
     * @Graph\PropertyName("favorited")
     * @Graph\PropertyType("Boolean")
     * @Graph\PropertyCardinality("SINGLE")
     */
    public $favorited;

    /**
     * @Serializer\Type("boolean")
     * @Serializer\Expose
     * @Serializer\Groups({"Default", "Graph"})
     * @Graph\PropertyName("retweeted")
     * @Graph\PropertyType("Boolean")
     * @Graph\PropertyCardinality("SINGLE")
     */
    public $retweeted;

    /**
     * @Serializer\Type("DateTime<'', '', 'D M d H:i:s P Y'>")
     * @Serializer\Expose
     * @Serializer\Groups({"Default", "Graph"})
     * @Graph\PropertyName("created_at")
     * @Graph\PropertyType("Date")
     * @Graph\PropertyCardinality("SINGLE")
     */
    public $created_at;

    /**
     * @Serializer\Type("string")
     * @Serializer\Expose
     * @Serializer\Groups({"Default", "Graph"})
     * @Graph\PropertyName("source")
     * @Graph\PropertyType("String")
     * @Graph\PropertyCardinality("SINGLE")
     */
    public $source;

    /**
     * @Serializer\Type("TheDonHimself\GremlinOGM\TwitterGraph\Graph\Vertices\Users")
     * @Serializer\Expose
     * @Serializer\Groups({"Default"})
     */
    public $user;

    /**
     * @Serializer\Type("TheDonHimself\GremlinOGM\TwitterGraph\Graph\Vertices\Tweets")
     * @Serializer\Expose
     * @Serializer\Groups({"Default"})
     */
    public $retweeted_status;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}
