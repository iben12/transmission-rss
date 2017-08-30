<?php

namespace App\Services;

use Guzzle\Http\Exception\BadResponseException;

class Transmission
{

    protected $transmission;

    public function __construct($config)
    {
        $this->config = $config;
        $this->transmission = new \Vohof\Transmission($config);
    }

    public function add($url, $title)
    {
        $dir = $this->config['shows_dir'] . $title . "/";
        return $this->transmission->add($url, false, ["download_dir" => $dir]);
    }

    public function cleanup()
    {
        $finished = $this->getFinished();

        foreach ($finished as $torrent) {
            $this->transmission->remove([$torrent['id']]);
        }

        return $finished;
    }

    private function getFinished()
    {
        $all = $this->transmission->get('all', ['id','name','isFinished'])['torrents'];

        return array_filter($all, function ($torrent) {
            return $torrent['isFinished'];
        });
    }
}
