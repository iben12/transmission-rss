<?php

namespace App\Notification;

interface NotificationProviderInterface
{
    public function push($title, $body);
}
