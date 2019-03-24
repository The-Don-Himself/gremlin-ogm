<?php

namespace TheDonHimself\GremlinOGM\DataType;

class GeoshapeCircle
{
    public $_geoshapecircle = array();

    /**
     * @param float $lat
     * @param float $lon
     * @param float $radius
     */
    public function __construct($lat, $lon, $radius)
    {
        $this->_geoshapecircle = array(
            'lat' => $lat,
            'lon' => $lon,
            'radius' => $radius,
        );
    }

    /**
     * @return float
     */
    public function getLat()
    {
        return $this->_geoshapecircle['lat'];
    }

    /**
     * @return float
     */
    public function getLon()
    {
        return $this->_geoshapecircle['lon'];
    }

    /**
     * @return float
     */
    public function getRadius()
    {
        return $this->_geoshapecircle['radius'];
    }
}
