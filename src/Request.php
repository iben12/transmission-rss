<?php

namespace App;

class Request
{
    public $request;
    public $uri;
    public $uriSegments;
    public $method;

    public function __construct()
    {
        $this->request = $_SERVER;
        $this->uri = str_replace(config("baseURI"), "", $this->request['REQUEST_URI']);
        $this->uriSegments = explode('/', $this->uri);
        $this->method = $this->request['REQUEST_METHOD'];
    }
}
