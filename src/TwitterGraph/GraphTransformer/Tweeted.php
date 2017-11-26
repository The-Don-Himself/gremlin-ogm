<?php

namespace TheDonHimself\GremlinOGM\TwitterGraph\GraphTransformer;

use DateTime;

class Tweeted
{
    public $tweet;
    public $tweeted_on; // The Twitter API doesn't provide this so it's just here for hypothetical reasons

    public function __construct(array $array)
    {
        foreach ($array as $key => $value) {
            if ('tweeted_on' == $key && isset($value[0])) {
                $timestamp = $value[0] / 1000;

                $date = new DateTime();
                $date->setTimestamp($timestamp);

                $this->$key = $date;
            }
        }

        $tweet = $array['tweet'] ?? array();
        $tweet ? $this->tweet = new Tweets($tweet) : null;
    }
}
