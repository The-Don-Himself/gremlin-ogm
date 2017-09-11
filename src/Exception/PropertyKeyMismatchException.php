<?php

namespace TheDonHimself\GremlinOGM\Exception;

use RuntimeException;

class PropertyKeyMismatchException extends RuntimeException implements GraphException
{
    /**
     * Creates an Property Key Mismatch Exception when trying to declare the same property key with different types or cardinalities.
     *
     * @param string $property_key_name
     * @param string $type
     * @param string $cardinality
     * @param string $new_type
     * @param string $new_cardinality
     * @param string $class
     *
     * @return PropertyKeyMismatchException
     */
    public function __construct(
        $property_key_name,
        $type,
        $cardinality,
        $new_type,
        $new_cardinality,
        $class
    ) {
        $message = '';
        $message = $message.'========== Property Key Mismatch =========='.PHP_EOL;
        $message = $message.'Property Key                    : '.$property_key_name.PHP_EOL;
        $message = $message.'Already Found Type              : '.$type.PHP_EOL;
        $message = $message.'Already Found Cardinality       : '.$cardinality.PHP_EOL;
        $message = $message.'Conflicting With Type           : '.$new_type.PHP_EOL;
        $message = $message.'Conflicting With Cardinality    : '.$new_cardinality.PHP_EOL;
        $message = $message.'Class                           : '.$class.PHP_EOL;

        parent::__construct($message);
    }
}
