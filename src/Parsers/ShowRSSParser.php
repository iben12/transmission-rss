<?php
namespace App\Parsers;

use App\Models\Episode;


class ShowRSSParser extends BaseParser implements FeedParserInterface
{
    public function parseFeed($feedItems)
    {
        $episodes = [];

        foreach($feedItems as $item) {
            $episode = new Episode();
            $episode->show_title = $item->getElementsByTagNameNS('http://showrss.info','show_name')->item(0)->nodeValue;
            $episode->link = $item->getElementsByTagName('link')->item(0)->nodeValue;
            $episode->show_id = $item->getElementsByTagNameNS('http://showrss.info','show_id')->item(0)->nodeValue;
            $episode->episode_id = $item->getElementsByTagNameNS('http://showrss.info','episode_id')->item(0)->nodeValue;
            $episode->title = $item->getElementsByTagNameNS('http://showrss.info','raw_title')->item(0)->nodeValue;

            $episodes[] = $episode;
        }

        return $episodes;
    }
}