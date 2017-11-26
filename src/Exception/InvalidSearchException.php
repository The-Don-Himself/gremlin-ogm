<?php

namespace TheDonHimself\GremlinOGM\Exception;

use RuntimeException;

class InvalidSearchException extends RuntimeException implements GraphException
{
    /**
     * Creates an Invalid Search Exception when a bad search parameter is submitted.
     *
     * @param string $predicate
     * @param string $message
     *
     * @return UnserializableException
     */
    public function __construct($predicate, $explanation = null)
    {
        $message = '';
        $message = $message.'========== Unserializable Exception =========='.PHP_EOL;
        if ($explanation) {
            $message = $message.'Explanation                      : '.$explanation.PHP_EOL;
        }
        $message = $message.'Predicate                              : '.$predicate.PHP_EOL;

        parent::__construct($message);
    }
}
