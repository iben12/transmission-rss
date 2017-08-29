<?php

namespace App\Services;

use App\Notification\NotificationServiceInterface;

class Pushbullet implements NotificationServiceInterface
{
    private $token;

    public function __construct()
    {
        $this->getConfig();
    }

    private function getConfig()
    {
        $config = require(__DIR__.'/../config.php');
        $this->token = $config["pushbullet"]["token"];
    }

    public function push($title, $body)
    {
        $message = [
            "title" => $title,
            "body" => $body,
            "type" => "note"
        ];
        curl_setopt_array(
            $push = curl_init(),
            [
                CURLOPT_URL => "https://api.pushbullet.com/v2/pushes",
                CURLOPT_HTTPHEADER => [
                    'Content-Type: application/json',
                    'Access-Token: ' . $this->token
                ],
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POSTFIELDS => json_encode($message)
            ]
        );
        $result = curl_exec($push);
        curl_close($push);
        return $result;
    }
}
