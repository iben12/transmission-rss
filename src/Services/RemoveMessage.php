<?php

namespace App\Services;

use App\Services\Message;

class RemoveMessage extends Message
{
    public function __construct($service)
    {
        parent::__construct($service);
    }

    public function sendRemoved($episodes)
    {
        $this->send('TransmissionRSS: Removed episode(s)', $this->renderBody($episodes));
    }

    private function renderBody($episodes)
    {
        $body = "Removed episodes:\n";
        foreach ($episodes as $episode) {
            $body .= $episode->show_title . ": " . $episode->title . "\n";
        }

        return $body;
    }
}
