<?php

namespace App\Notification;

class Slack implements NotificationProviderInterface
{
    public function push($title, $body)
    {
        $message = [
            "channel" => config('slack.channel'),
            "text" => $title,
            "blocks" => [
                [
                    "type" => "section",
                    "text" => [
                        "text" => ":arrow_up_down: *" . $title . "*\n" . "$body",
                        "type" => "mrkdwn"
                    ]
                ]
            ]
        ];
        curl_setopt_array(
            $push = curl_init(),
            [
                CURLOPT_URL => config('slack.webhook'),
                CURLOPT_HTTPHEADER => [
                    'Content-Type: application/json'
                ],
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POSTFIELDS => json_encode($message)
            ]
        );
        curl_exec($push);
        curl_close($push);
    }
}
