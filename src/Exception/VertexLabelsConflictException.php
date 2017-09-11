<?php

namespace TheDonHimself\GremlinOGM\Exception;

use RuntimeException;

class VertexLabelsConflictException extends RuntimeException implements GraphException
{
    /**
     * Creates an Vertex Labels Conflict Exception when trying to declare the same vertex label twice.
     *
     * @param string $label
     * @param string $class
     *
     * @return VertexLabelsConflictException
     */
    public function __construct($label, $class)
    {
        $message = '';
        $message = $message.'========== Vertex Labels Conflict =========='.PHP_EOL;
        $message = $message.'Already Found Vertex Label       : '.$label.PHP_EOL;
        $message = $message.'Class                            : '.$class.PHP_EOL;

        parent::__construct($message);
    }
}
