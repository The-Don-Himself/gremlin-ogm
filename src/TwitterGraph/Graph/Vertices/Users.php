<?php

namespace TheDonHimself\GremlinOGM\TwitterGraph\Graph\Vertices;

use JMS\Serializer\Annotation as Serializer;
use TheDonHimself\GremlinOGM\Annotation as Graph;

/**
 *  @Serializer\ExclusionPolicy("all")
 *  @Graph\Vertex(
 *      label="users",
 *      indexes={
 *          @Graph\Index(
 *              name="byUsersIdComposite",
 *              type="Composite",
 *              unique=true,
 *              label_constraint=true,
 *              keys={
 *                  "users_id"
 *              }
 *          ),
 *          @Graph\Index(
 *              name="byScreenNameComposite",
 *              type="Composite",
 *              unique=true,
 *              label_constraint=true,
 *              keys={
 *                  "screen_name"
 *              }
 *          ),
 *          @Graph\Index(
 *              name="usersMixed",
 *              type="Mixed",
 *              label_constraint=true,
 *              keys={
 *                  "users_id"        : "DEFAULT",
 *                  "name"            : "TEXTSTRING",
 *                  "screen_name"     : "STRING",
 *                  "description"     : "TEXT",
 *                  "followers_count" : "DEFAULT",
 *                  "created_at"      : "DEFAULT",
 *                  "verified"        : "DEFAULT",
 *                  "lang"            : "STRING"
 *              }
 *          )
 *      }
 *  )
 */
class Users
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
     * @Serializer\SerializedName("users_id")
     * @Graph\Id
     * @Graph\PropertyName("users_id")
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
     * @Graph\PropertyName("name")
     * @Graph\PropertyType("String")
     * @Graph\PropertyCardinality("SINGLE")
     */
    public $name;

    /**
     * @Serializer\Type("string")
     * @Serializer\Expose
     * @Serializer\Groups({"Default", "Graph"})
     * @Graph\PropertyName("screen_name")
     * @Graph\PropertyType("String")
     * @Graph\PropertyCardinality("SINGLE")
     */
    public $screen_name;

    /**
     * @Serializer\Type("string")
     * @Serializer\Expose
     * @Serializer\Groups({"Default", "Graph"})
     * @Graph\PropertyName("description")
     * @Graph\PropertyType("String")
     * @Graph\PropertyCardinality("SINGLE")
     */
    public $description;

    /**
     * @Serializer\Type("integer")
     * @Serializer\Expose
     * @Serializer\Groups({"Default", "Graph"})
     * @Graph\PropertyName("followers_count")
     * @Graph\PropertyType("Integer")
     * @Graph\PropertyCardinality("SINGLE")
     */
    public $followers_count;

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
     * @Serializer\Type("boolean")
     * @Serializer\Expose
     * @Serializer\Groups({"Default", "Graph"})
     * @Graph\PropertyName("verified")
     * @Graph\PropertyType("Boolean")
     * @Graph\PropertyCardinality("SINGLE")
     */
    public $verified;

    /**
     * @Serializer\Type("string")
     * @Serializer\Expose
     * @Serializer\Groups({"Default", "Graph"})
     * @Graph\PropertyName("lang")
     * @Graph\PropertyType("String")
     * @Graph\PropertyCardinality("SINGLE")
     */
    public $lang;

    /**
     * @Serializer\Type("TheDonHimself\GremlinOGM\TwitterGraph\Graph\Vertices\Tweets")
     * @Serializer\Expose
     * @Serializer\Groups({"Default"})
     */
    public $status;

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
