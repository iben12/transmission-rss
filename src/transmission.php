<?php

Class Transmission {
    public $svc;
    private $config;

    function __construct()
    {
        $this->getConfig();
        $this->svc = new Vohof\Transmission($this->config);
    }

    private function getConfig() {
        $config = file_get_contents(__DIR__ . '/../config.json');
        $config = json_decode($config, true);
        $this->config = $config['transmission'];
    }
}