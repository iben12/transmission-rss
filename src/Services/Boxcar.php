<?php

namespace App\Services;

Class Boxcar {

    private $token;
    
    function __construct()
    {
<<<<<<< HEAD:src/boxcar.php
        $this->getConfig();
        $this->token = $this->config->token;
    }

    private function getConfig() {
        $config = file_get_contents(__DIR__ . '/../config.json');
        $config = json_decode($config, true);
        $this->config = $config['boxcar'];
=======
        $config = require(__DIR__.'/../config.php');
        $this->token = $config["boxcar"]["token"];
>>>>>>> dev:src/Services/Boxcar.php
    }

    public function push($title, $body) {
        curl_setopt_array(
            $push = curl_init(),
            [
                CURLOPT_URL => "https://new.boxcar.io/api/notifications",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POSTFIELDS => [
                    "user_credentials" => $this->token,
                    "notification[title]" => $title,
                    "notification[long_message]" => $body,
                    "notification[sound]" => "beep-soft",
<<<<<<< HEAD:src/boxcar.php
                )
            )
        );
        $ret = curl_exec($push);
=======
                ]
            ]
        );
        curl_exec($push);
>>>>>>> dev:src/Services/Boxcar.php
        curl_close($push);
    }
}