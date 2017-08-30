<?php

namespace App\Notification;

class NotificationProviderFactory
{
    public static function getProvider($serviceName)
    {
        $provider = config($serviceName.".provider");
        return new $provider;
    }
}
