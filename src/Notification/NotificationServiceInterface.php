<?php

namespace App\Notification;

interface NotificationServiceInterface
{
    public function push($title, $body);
}
