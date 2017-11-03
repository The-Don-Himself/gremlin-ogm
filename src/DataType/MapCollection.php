<?php

namespace TheDonHimself\GremlinOGM\DataType;

use JMS\Serializer\Annotation as Serializer;

/**
 *  @Serializer\ExclusionPolicy("all")
 */
class MapCollection
{
    /**
     * @param array
     * @Serializer\Expose
     * @Serializer\Groups({"Default", "Graph"})
     * @Serializer\Type("array")
     * @Serializer\SerializedName("_map_collection")
     */
    public $_map_collection = array();

    /**
     * @param array $array
     */
    public function __construct(array $array)
    {
        $this->_map_collection = $array;
    }

    /**
     * @return _map_collection
     */
    public function getMapCollection()
    {
        return $this->_map_collection;
    }

}
