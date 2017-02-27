<?php

namespace App\Parsers;


interface FeedParserInterface
{
    public function parseFeed($feedItems);
    public function parse($xml);
}