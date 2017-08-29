<?php

namespace App\Notification;

class NotificationServiceFactory
{
    private static $services = [
        'boxcar' => \App\Services\Boxcar::class,
        'pushbullet' => \App\Services\Pushbullet::class
    ];

    public static function getService($key)
    {
        return new self::$services[$key];
    }
}
