<?php

namespace App\Services;

use App\Models\Episode;
use App\Parsers\FeedParserInterface;
use Exception;

class Feed
{
    protected $url;
    protected $type;

    /**
     * @var FeedParserInterface $feedParser
     */
    protected $feedParser;

    public function __construct($parsers, $data)
    {
        $this->url = $data["url"];
        $this->type = $data["type"];
        $this->feedParser = $this->getParser($parsers);
    }

    /**
     * @param $parsers
     * @return mixed
     * @throws Exception
     */
    private function getParser($parsers)
    {
        if (array_key_exists($this->type, $parsers)) {
            return new $parsers[$this->type]();
        } else {
            throw new Exception('Parser not found');
        }
    }

    private function fetch()
    {
        try {
            $raw = file_get_contents($this->url);
        } catch (Exception $e) {
            return false;
        }
        return $raw;
    }

    public function newEpisodes()
    {
        $xml = $this->fetch();

        if (!$xml) {
            return [];
        }

        $newEpisodes = array_filter($this->feedParser->parse($xml), function ($episode) {
            return $this->isNew($episode);
        });
        return $newEpisodes;
    }

    private function isNew($episode)
    {
        $existing = Episode::objects()
            ->filter('episode_id', '=', $episode->episode_id)
            ->filter('show_id', '=', $episode->show_id)
            ->fetch();

        if (!$existing) {
            return true;
        } else {
            return false;
        }
    }
}
