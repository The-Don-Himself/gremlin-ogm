<?php

namespace TheDonHimself\GremlinOGM\DataType;

class GeoshapeBox
{
    public $_geoshapebox = array();

    /**
     * @param float $sw_lat
     * @param float $sw_lon
     * @param float $ne_lat
     * @param float $ne_lon
     */
    public function __construct($sw_lat, $sw_lon, $ne_lat, $ne_lon)
    {
        $this->_geoshapebox = array(
            'sw_lat' => $sw_lat,
            'sw_lon' => $sw_lon,
            'ne_lat' => $ne_lat,
            'ne_lon' => $ne_lon,
        );
    }

    /**
     * @return float
     */
    public function getSwLat()
    {
        return $this->_geoshapebox['sw_lat'];
    }

    /**
     * @return float
     */
    public function getSwLon()
    {
        return $this->_geoshapebox['sw_lon'];
    }

    /**
     * @return float
     */
    public function getNeLat()
    {
        return $this->_geoshapebox['ne_lat'];
    }

    /**
     * @return float
     */
    public function getNeLon()
    {
        return $this->_geoshapebox['ne_lon'];
    }

}
