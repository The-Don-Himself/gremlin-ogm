<?php

namespace TheDonHimself\GremlinOGM\DataType;

class GeoshapeCircle
{
    public $_geoshapecircle = array();
    private $lat;
    private $lon;
    private $radius;

    /**
     * @param float $lat
     * @param float $lon
     * @param float $radius
     */
    public function __construct($lat, $lon, $radius)
    {
        $this->lat = $lat;
        $this->lon = $lon;
        $this->radius = $radius;
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
        return $this->lat;
    }

    /**
     * @return float
     */
    public function getLon()
    {
        return $this->lon;
    }

    /**
     * @return float
     */
    public function getRadius()
    {
        return $this->radius;
    }
}
