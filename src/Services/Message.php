<?php

namespace App\Services;

use App\Notification\NotificationServiceFactory;

class Message
{
    protected $service;

    public function __construct($service)
    {
        $this->service = $service;
    }

    public function send($title, $body)
    {
        $this->service->push($title, $body);
    }
}
