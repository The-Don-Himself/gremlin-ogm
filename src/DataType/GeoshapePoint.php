<?php

namespace TheDonHimself\GremlinOGM\DataType;

class GeoshapePoint
{
    public $_geoshapepoint = array();
    private $lat;
    private $lon;

    /**
     * @param float $lat
     * @param float $lon
     */
    public function __construct($lat, $lon)
    {
        $this->lat = $lat;
        $this->lon = $lon;
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
        return $this->lat;
    }

    /**
     * @return float
     */
    public function getLon()
    {
        return $this->lon;
    }
}
