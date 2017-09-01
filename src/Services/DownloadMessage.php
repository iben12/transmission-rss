<?php

namespace App\Services;

use App\Services\Message;

class DownloadMessage extends Message
{
    public function __construct($service)
    {
        parent::__construct($service);
    }

    public function sendDownloads($episodes)
    {
        $this->send('TransmissionRSS: New episode(s)', $this->renderBody($episodes));
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
