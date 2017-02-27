<?php

include __DIR__.'/../../vendor/autoload.php';

$api = new \App\Api();

$new = $api->download();

exit;