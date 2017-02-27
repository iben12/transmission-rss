<?php

namespace App\Parsers;


use Lightools\Xml\XmlLoader;

abstract class BaseParser
{
    public function parse($xml)
    {
        $feedItems = $this->parseXML($xml);

        if (!$feedItems) {
            return [];
        }

        return $this->parseFeed($feedItems);
    }

    abstract public function parseFeed($feedItems);

    private function parseXML($xml)
    {
        try {
            $parser = new XmlLoader();
            $parsed_rss = $parser->loadXml($xml);
            $items = $parsed_rss->getElementsByTagName('item');
        } catch(\Exception $e) {
            return false;
        }
        return $items;
    }
}