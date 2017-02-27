<?php

return [
    "feeds" => [
        [
            "name" =>"ShowRSS",
            "url" =>"http://showrss.info/show/749.rss",
            "type" =>"showRSS"
        ]
    ],

    "baseURI" => "",

    "parsers" => [
        'showRSS' => \App\Parsers\ShowRSSParser::class
    ],

    "transmission" => [
        "host" =>"http://example.com:9091",
        "endpoint" => "/transmission/rpc",
        "username" =>"username",
        "password" =>"password",
        "shows_dir" => "/path/to/tv-shows/folder"
    ],
    "orm" => [
        "databases" => [
            "sqlite" => [
                "dsn" => "sqlite:".__DIR__."/trrss_db.sqlite"
            ]
        ]
    ],
    "boxcar" => [
        "active" => false,
        "token" => "your-boxcar-token"
    ]
];


