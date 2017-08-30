<?php

namespace App\Notification;

class Boxcar implements NotificationProviderInterface
{
    public function push($title, $body)
    {
        curl_setopt_array(
            $push = curl_init(),
            [
                CURLOPT_URL => "https://new.boxcar.io/api/notifications",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POSTFIELDS => [
                    "user_credentials" => config("boxcar.token"),
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
