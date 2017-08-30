<?php

/**
 * @param string $key
 * @return mixed
 */
function config($key)
{
    $file = require(__DIR__.'/../../config.php');
    $tree = explode('.', $key);

    $value = $file;

    foreach ($tree as $level) {
        $value = $value[$level];
    }

    return $value;
}
