<?php

namespace TheDonHimself\GremlinOGM\DataType;

class GeoshapeBox
{
    public $_geoshapebox = array();
    private $sw_lat;
    private $sw_lon;
    private $ne_lat;
    private $ne_lon;

    /**
     * @param float $sw_lat
     * @param float $sw_lon
     * @param float $ne_lat
     * @param float $ne_lon
     */
    public function __construct($sw_lat, $sw_lon, $ne_lat, $ne_lon)
    {
        $this->sw_lat = $sw_lat;
        $this->sw_lon = $sw_lon;
        $this->ne_lat = $ne_lat;
        $this->ne_lon = $ne_lon;
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
        return $this->sw_lat;
    }

    /**
     * @return float
     */
    public function getSwLon()
    {
        return $this->sw_lon;
    }

    /**
     * @return float
     */
    public function getNeLat()
    {
        return $this->ne_lat;
    }

    /**
     * @return float
     */
    public function getNeLon()
    {
        return $this->ne_lon;
    }
}
