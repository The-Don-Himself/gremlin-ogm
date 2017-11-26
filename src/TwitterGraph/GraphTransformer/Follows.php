<?php

namespace TheDonHimself\GremlinOGM\TwitterGraph\GraphTransformer;

use DateTime;

class Follows
{
    public $user;
    public $followed_on; // The Twitter API doesn't provide this so it's just here for hypothetical reasons

    public function __construct(array $array)
    {
        foreach ($array as $key => $value) {
            if ('followed_on' == $key && isset($value[0])) {
                $timestamp = $value[0] / 1000;

                $date = new DateTime();
                $date->setTimestamp($timestamp);

                $this->$key = $date;
            }
        }

        $user = $array['user'] ?? array();
        $user ? $this->user = new Users($user) : null;
    }
}
