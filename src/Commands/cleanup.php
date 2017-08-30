<?php

include __DIR__.'/../../vendor/autoload.php';

$api = new \App\Api();

$removed = $api->cleanup();

if (count($removed) > 0 && config('notification.active')) {
    $title = "TransmissionRSS: Removed torrents";
    $body = "Removed the following finished torrents:\n";
    
    foreach ($removed as $torrent) {
        $body .= $torrent["name"] . " (" . $torrent["id"] . ")\n";
    }
    $service = config('notification.service');
    $notifier = config($service.'.provider');
    $notifier::push($title, $body);
}

exit;
