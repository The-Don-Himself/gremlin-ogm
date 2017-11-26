<?php

namespace TheDonHimself\GremlinOGM\TwitterGraph\GraphTransformer;

use DateTime;

class Tweets
{
    public $id;
    public $text;
    public $retweet_count;
    public $favorited;
    public $retweeted;
    public $created_at;
    public $user;
    public $source;
    public $retweeted_status;

    public function __construct(array $array)
    {
        $tweet = $array['tweet'] ?? $array;

        foreach ($tweet as $key => $value) {
            if ('tweets_id' == $key && isset($value[0])) {
                $this->id = $value[0];
            } elseif ('created_at' == $key && isset($value[0])) {
                $timestamp = $value[0] / 1000;

                $date = new DateTime();
                $date->setTimestamp($timestamp);

                $this->$key = $date;
            } else {
                $this->$key = $value[0];
            }
        }

        $user = $array['user'] ?? array();
        $user ? $this->user = new Users($user) : null;
    }
}
