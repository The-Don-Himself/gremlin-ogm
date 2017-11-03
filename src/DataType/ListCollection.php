<?php

namespace TheDonHimself\GremlinOGM\DataType;

use JMS\Serializer\Annotation as Serializer;

/**
 *  @Serializer\ExclusionPolicy("all")
 */
class ListCollection
{
    /**
     * @param array
     * @Serializer\Expose
     * @Serializer\Groups({"Default", "Graph"})
     * @Serializer\Type("array")
     * @Serializer\SerializedName("_list_collection")
     */
    public $_list_collection = array();

    /**
     * @param array $array
     */
    public function __construct(array $array)
    {
        $this->_list_collection = $array;
    }

    /**
     * @return _list_collection
     */
    public function getListCollection()
    {
        return $this->_list_collection;
    }

}
