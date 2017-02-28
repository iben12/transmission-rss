<?php

namespace App;

use App\Models\Episode;
use App\Services\Feed;
use App\Services\Message;
use App\Services\Transmission;
use Guzzle\Http\Exception\BadResponseException;
use Phormium\Orm;

Class Api {

    protected $config;

    function __construct()
    {
        $this->config = require('config.php');
        Orm::configure($this->config["orm"]);
    }

    public function index(Request $request)
    {
        $methodCall = $request->uriSegments[2];
        if ( method_exists($this, $methodCall) ) {
            return $this->$methodCall();
        } else {
            header("HTTP/1.0 404 Not Found");
            exit;
        }
    }

    /**
     * @return array
     */
    public function episodes()
    {
        return Episode::all();
    }

    /**
     * @return array
     */
    public function feeds()
    {
        return $this->config["feeds"];
    }

    public function fetch()
    {
        $feeds = $this->feeds();

        $newEpisodes = [];

        foreach($feeds as $feedData) {
            try {
                $feed = new Feed($this->config["parsers"], $feedData);
            }
            catch (\Exception $e) {
                continue;
            }

            $newEpisodes = array_merge($newEpisodes,$feed->newEpisodes());
        }

        return $newEpisodes;
    }

    /**
     * @return array
     */
    public function download()
    {
        $new = $this->fetch();

        if (count($new) < 1) {
            return [];
        }

        $transmission = new Transmission($this->config["transmission"]);

        $downloading = [];

        foreach ($new as $episode)
        {
            if ($this->config["transmission"]["active"]) {
                try {
                    $transmission->add($episode->link, $episode->show_title);
                }
                catch (\Exception $e) {
                    continue;
                    // TODO: notify user
                }
            }

            $downloading[] = $episode;
            $episode->created_at = date("Y-m-d H:i:s");
            $episode->save();
        }

        if ($this->config["boxcar"]["active"]) {
            $msg = new Message();
            $msg->send($downloading);
        }

        return $downloading;
    }

    public function cleanup()
    {
        $transmission = new Transmission($this->config["transmission"]);

        $removed = $transmission->cleanup();

        return $removed;
    }

}