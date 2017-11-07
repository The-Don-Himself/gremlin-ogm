<?php

namespace TheDonHimself\GremlinOGM\DataType;

use JMS\Serializer\Annotation as Serializer;

/**
 *  @Serializer\ExclusionPolicy("all")
 */
class HashMap
{
    /**
     * @param array
     * @Serializer\Expose
     * @Serializer\Groups({"Default", "Graph"})
     * @Serializer\Type("array")
     * @Serializer\SerializedName("_hash_map")
     */
    public $_hash_map = array();

    /**
     * @param array $array
     */
    public function __construct($array)
    {
        $this->_hash_map = $array;
    }

    /**
     * @return _hash_map
     */
    public function getHashMap()
    {
        return $this->_hash_map;
    }
}
