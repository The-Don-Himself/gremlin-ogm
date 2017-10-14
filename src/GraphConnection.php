<?php

namespace TheDonHimself\GremlinOGM;

use Brightzone\GremlinDriver\Connection;

class GraphConnection
{
    /**
     * @var array contains the options to connect to the database with
     */
    public $options = [];

    public function __construct($options = [])
    {
        if (!isset($options['graph'])) {
            $options['graph'] = 'graph';
        }
        if (!isset($options['emptySet'])) {
            $options['emptySet'] = true;
        }
        if (!isset($options['timeout'])) {
            $options['timeout'] = 5;
        }

        $this->options = $options;
    }

    public function init()
    {
        $options = $this->options;

        $db = new Connection($options);

        return $db;
    }
}
