<?php

namespace App;

use App\Models\Episode;
use App\Services\Feed;
use App\Services\DownloadMessage;
use App\Services\Transmission;
use App\Notification\NotificationProviderFactory;
use Guzzle\Http\Exception\BadResponseException;
use Phormium\DB;

class Api
{
    public function __construct()
    {
        DB::configure(config("orm"));
    }

    public function index(Request $request)
    {
        $methodCall = $request->uriSegments[2];
        if (method_exists($this, $methodCall)) {
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
        return config("feeds");
    }

    public function fetch()
    {
        $feeds = $this->feeds();

        $newEpisodes = [];

        foreach ($feeds as $feedData) {
            try {
                $feed = new Feed($feedData);
            } catch (\Exception $e) {
                continue;
            }

            $newEpisodes = array_merge($newEpisodes, $feed->newEpisodes());
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

        $transmission = new Transmission(config("transmission"));

        $downloading = [];

        foreach ($new as $episode) {
            if (config("transmission.active")) {
                try {
                    $transmission->add($episode->link, $episode->show_title);
                } catch (\Exception $e) {
                    continue;
                    // TODO: notify user
                }
            }

            $downloading[] = $episode;
            $episode->created_at = date("Y-m-d H:i:s");
            $episode->save();
        }

        if (config("notification.active")) {
            $activeService = config("notification.service");
            $msg = new DownloadMessage(NotificationProviderFactory::getProvider($activeService));
            $msg->sendDownloads($downloading);
        }

        return $downloading;
    }

    public function cleanup()
    {
        $transmission = new Transmission(config("transmission"));

        $removed = $transmission->cleanup();

        return $removed;
    }
}
