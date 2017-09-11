<?php

namespace TheDonHimself\GremlinOGM\Exception;

use RuntimeException;

class IndexNamesConflictException extends RuntimeException implements GraphException
{
    /**
     * Creates an Vertex Labels Conflict Exception when trying to declare the same vertex label twice.
     *
     * @param string $name
     * @param string $class
     *
     * @return IndexNamesConflictException
     */
    public function __construct($name, $class)
    {
        $message = '';
        $message = $message.'========== Index Names Conflict =========='.PHP_EOL;
        $message = $message.'Already Found Index Name         : '.$name.PHP_EOL;
        $message = $message.'Class                            : '.$class.PHP_EOL;

        parent::__construct($message);
    }
}
