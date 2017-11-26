<?php

namespace TheDonHimself\GremlinOGM\TwitterGraph\GraphTransformer;

use DateTime;

class Users
{
    public $id;
    public $name;
    public $screen_name;
    public $profile_image_url_https;
    public $description;
    public $followers_count;
    public $created_at;
    public $verified;
    public $lang;
    public $status;

    public function __construct(array $array)
    {
        $user = $array['user'] ?? $array;

        foreach ($user as $key => $value) {
            if ('users_id' == $key && isset($value[0])) {
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

        $status = $array['status'] ?? array();
        $status ? $this->status = new Tweets($status) : null;
    }
}
