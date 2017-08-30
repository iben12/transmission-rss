<?php

namespace App\Notification;

class Pushbullet implements NotificationProviderInterface
{
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
                    'Access-Token: ' . config('pushbullet.token')
                ],
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POSTFIELDS => json_encode($message)
            ]
        );
        curl_exec($push);
        curl_close($push);
    }
}
