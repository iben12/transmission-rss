<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use App\Services\Transmission;
use \Guzzle\Http\Exception\CurlException;

class TransmissionTest extends TestCase
{
    public function testAddFails()
    {
        $config = [
            "active" => false,
            "host" => "http://iben.space:9090",
            "endpoint" => "/transmission/rpc",
            "username" => "username",
            "password" => "password",
            "shows_dir" => "/path/to/tv-shows/folder/" // trailing slash is important!
        ];

        $client = new Helpers\TestGuzzleClient($config["host"]);

        $transmission = new Transmission($config, $client);

        $this->expectException(CurlException::class);

        $transmission->add("some-url", "some-title");
    }
}
