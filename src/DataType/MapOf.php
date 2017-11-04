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
     * @Serializer\SerializedName("_map_of")
     */
    public $_map_of = array();

    /**
     * @param array $array
     */
    public function __construct(array $array)
    {
        $this->_map_of = $array;
    }

    /**
     * @return _map_of
     */
    public function getMapOf()
    {
        return $this->_map_of;
    }
}
