<?php

namespace TheDonHimself\GremlinOGM\DataType;

class GeoshapePoint
{
    public $_geoshapepoint = array();

    /**
     * @param float $lat
     * @param float $lon
     */
    public function __construct($lat, $lon)
    {
        $this->_geoshapepoint = array(
            'lat' => $lat,
            'lon' => $lon,
        );
    }

    /**
     * @return float
     */
    public function getLat()
    {
        return $this->_geoshapepoint['lat'];
    }

    /**
     * @return float
     */
    public function getLon()
    {
        return $this->_geoshapepoint['lon'];
    }
}
