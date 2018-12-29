<?php

namespace TheDonHimself\GremlinOGM;

use Brightzone\GremlinDriver\Connection;

class GraphConnection
{
    const DEFAULT_OPTIONS = array(
        'host' => '127.0.0.1',
        'port' => 8182,
        'username' => null,
        'password' => null,
        'ssl' => array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
            ),
        ),
        'graph' => 'graph',
        'timeout' => 10,
        'emptySet' => true,
        'retryAttempts' => 3,
    );

    /**
     * @var \Brightzone\GremlinDriver\Connection
     */
    private $connection;

    /**
     * @var array contains the options to connect to the database with
     */
    private $options = [];

    /**
     * @var array contains the twitter app credentials for sample Twitter Graph
	 * https://github.com/The-Don-Himself/twitter-graph
     */
    private $twitter = [];

    /**
     * @var array contains the vendor options to cater for different implementations
     */
    private $vendor = [];

    public function __construct($options = [])
    {
        if (!isset($options['graph'])) {
            $options['graph'] = 'graph';
        }
        if (!isset($options['emptySet'])) {
            $options['emptySet'] = true;
        }
        if (!isset($options['timeout'])) {
            $options['timeout'] = 10;
        }

        if (isset($options['twitter'])) {
            $this->twitter = $options['twitter'];
            unset($options['twitter']);
        }

        if (isset($options['vendor'])) {
            $this->vendor = $options['vendor'];
            unset($options['vendor']);
        }

        $this->options = $options;
    }

    public function init()
    {
        $options = $this->options;

        $db = new Connection($options);
        $db->message->registerSerializer('\Brightzone\GremlinDriver\Serializers\Gson3', true);

        $this->connection = $db;

        return $this;
    }

    public function getConnection()
    {
        $connection = $this->connection;

        return $connection;
    }

    public function getTwitter()
    {
        $twitter = $this->twitter;

        return $twitter;
    }

    public function getVendor()
    {
        $vendor = $this->vendor;

        return $vendor;
    }
}
