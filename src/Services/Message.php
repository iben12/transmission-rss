<?php

namespace App\Services;


class Message
{
    protected $boxcar;
    protected $title;

    public function __construct()
    {
        $this->boxcar = new Boxcar();
        $this->title = 'TransmissionRSS: New episode(s).';
    }

    public function send($episodes)
    {
        $body = $this->renderBody($episodes);

        $this->boxcar->push($this->title, $body);
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