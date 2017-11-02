<?php

namespace TheDonHimself\GremlinOGM\DataType;

use JMS\Serializer\Annotation as Serializer;

/**
 *  @Serializer\ExclusionPolicy("all")
 */
class MapOf
{
    /**
     * @param array
     * @Serializer\Expose
     * @Serializer\Groups({"Default", "Graph"})
     * @Serializer\Type("array")
     * @Serializer\SerializedName("_map")
     */
    public $_map = array();

    /**
     * @param array $array
     */
    public function __construct(array $array)
    {
        $this->_map = $array;
    }

    /**
     * @return _map
     */
    public function getMap()
    {
        return $this->_map;
    }

}
