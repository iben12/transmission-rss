<?php

namespace App\Services;

use \App\Notification\NotificationServiceInterface;

class Boxcar implements NotificationServiceInterface
{

    private $token;

    public function __construct()
    {
        $this->getConfig();
    }

    private function getConfig()
    {
        $config = require(__DIR__.'/../config.php');
        $this->token = $config["boxcar"]["token"];
    }

    public function push($title, $body)
    {
        curl_setopt_array(
            $push = curl_init(),
            [
                CURLOPT_URL => "https://new.boxcar.io/api/notifications",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POSTFIELDS => [
                    "user_credentials" => $this->token,
                    "notification[title]" => $title,
                    "notification[long_message]" => $body,
                    "notification[sound]" => "beep-soft",
                ]
            ]
        );
        curl_exec($push);
        curl_close($push);
    }
}
