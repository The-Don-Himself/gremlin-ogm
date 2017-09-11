<?php

namespace TheDonHimself\GremlinOGM\Exception;

use RuntimeException;

class UnserializableException extends RuntimeException implements GraphException
{
    /**
     * Creates an Unserializable Exception when trying to serialize arrays to string.
     *
     * @param string $key
     * @param string $message
     * @return UnserializableException
     */
    public function __construct($key, $explanation = null) {
        $message = '';
        $message = $message . '========== Unserializable Exception ==========' . PHP_EOL;
        if($explanation){
            $message = $message . 'Explanation                      : ' . $explanation . PHP_EOL;
        }
        $message = $message . 'Key                              : ' . $key . PHP_EOL;

        parent::__construct($message);
    }


}
