# Transmission RSS
**NOTE:** This is an experimental project not intended to be used on a public accessible host.

![screenshot](http://i.imgur.com/Dn13ZlV.png)

## What is this?
Transmission-RSS is a tool to fetch and parse RSS feeds, extract episode data and add them to transmission client through it's RPC interface.

## What it has?
* Web interface to display downloaded episodes and trigger actions (download, cleanup)
* Currently includes a [ShowRSS](https://show-rss.info) parser, but parsers can be added by implementing `FeedParserInterface`.
* [Boxcar](https://boxcar.io/) push notification integration
* Commands for download and cleanup to be run by `cron`
* SQLite DB to store downloaded episodes

## Usage
1. Download or clone the repo.
2. Open `src/config.php`
3. Set up your feed at [ShowRSS](https://show-rss.info) and get feed URL.
4. Enter your feed in `config.php`
5. If you want to use trss from a server subdirectory and it to the `baseURI` ex.
    ```php
    <?php
 
    return [
       // ...
       "baseURI" => "/your-directory",
     ]
    ```
6. Configure your transmission client's RPC access
    ```php
    <?php
 
    return [
       // ...
       "transmission" => [
           "host" =>"http://example.com:9091",
           "endpoint" => "/transmission/rpc",
           "username" =>"username",
           "password" =>"password",
           "shows_dir" => "/path/to/tv-shows/folder"
       ],
     ]
    ```
7. If you want to use [Boxcar](https://boxcar.io/), set up your account, get a token and save it in `config.php`
    ```php
    <?php
 
    return [
       // ...
       "boxcar" => [
           "active" => true,
           "token" => "your-token"
        ]
     ]
    ```
8. Basically you are ready to go.

## About
The project is a proof-of-concept. The aim was to make the service without any framework, but with OOP.
Pulling in an ORM appears to be overkill for the current use (storing episodes), but other `crud` features were planned.

The project consists of a minimal `router` implementation, an `api` class, XML parser, transmission RPC ([vohof/transmission](https://github.com/vohof/transmission)) and Boxcar client.

The frontend of the web interface was created with [Vue.js](https://vuejs.org/) and compiled with [Webpack](http://webpack.github.io/). To watch and compile assets on change, run `webpack`. 

## Extending
New parsers can be added through the `FeedParserInterface`. Make a class in `src/Parsers` that extends the `BaseParser` class. This implements the `FeedParserInteface` and already has implementation for XML parsing (`parse()`).
You should only write the `parseFeed()` method based on the actual feed like the ShowRSS parser:
```php
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
```

## TODO
* Create interface for notifications for implementations other than Boxcar
* ...