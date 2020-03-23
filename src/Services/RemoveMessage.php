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

    private function renderBody($removed)
    {
        $body = "Removed episodes:\n";
        foreach ($removed as $torrent) {
            $body .= $torrent['name'] . "\n";
        }

        return $body;
    }
}
