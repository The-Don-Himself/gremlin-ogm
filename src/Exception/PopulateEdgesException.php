<?php

namespace TheDonHimself\GremlinOGM\Exception;

use RuntimeException;

class PopulateEdgesException extends RuntimeException implements GraphException
{
    /**
     * Creates an Populate Edges Exception when trying to populate an edge class.
     *
     * @param string $class
     * @param string $message
     *
     * @return PopulateEdgesException
     */
    public function __construct($class, $explanation = null)
    {
        $message = '';
        $message = $message.'========== Populate Edges Exception =========='.PHP_EOL;
        if ($explanation) {
            $message = $message.'Explanation                      : '.$explanation.PHP_EOL;
        }
        $message = $message.'Class                            : '.$class.PHP_EOL;

        parent::__construct($message);
    }
}
