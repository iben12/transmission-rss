<?php

namespace App\Services;

use App\Notification\NotificationServiceFactory;

class Message
{
    protected $service;
    protected $title;

    public function __construct($service)
    {
        $this->service = NotificationServiceFactory::getService($service);
        $this->title = 'TransmissionRSS: New episode(s).';
    }

    public function send($episodes)
    {
        $body = $this->renderBody($episodes);

        $this->service->push($this->title, $body);
    }

    private function renderBody($episodes)
    {
        $body = "Added episodes:<br>";
        foreach ($episodes as $episode) {
            $body .= $episode->show_title . ": " . $episode->title . '<br>';
        }

        return $body;
    }
}
