<?php

include __DIR__.'/../../vendor/autoload.php';

$api = new \App\Api();

$removed = $api->cleanup();

echo "Removed torrents:\n";

foreach($removed as $torrent) {
    echo $torrent["name"] . " (" . $torrent["id"] . ")\n";
}

exit;