<?php

namespace App;

class Router
{
    protected $request;

    public function __construct()
    {
        $this->request = new Request();
    }

    public function get($uriPattern, $closure) {
        if ($this->request->method == "GET" && $this->matchPattern($uriPattern)) {
            $this->response($closure);
        }
        return;
    }

    private function matchPattern($uriPattern)
    {
        if ($uriPattern == $this->request->uri) return true;

        if ( preg_match("/\/\*$/",$uriPattern) ) { // uri ends with "/*"
            $uriPattern = str_replace('/*','',$uriPattern);
            return strpos($this->request->uri,$uriPattern) > -1;
        }
        return false;
    }

    private function response($closure)
    {
        $callable = $this->getCallable($closure);

        $responseBody = call_user_func($callable,$this->request);

        if ($this->request->uriSegments[1] == 'api') {
            $responseBody = json_encode($responseBody);
        }
        echo $responseBody;
        exit;
    }

    private function getCallable($closure)
    {
        if (!is_callable($closure)) {
            $closure = explode('@',$closure);
            $className = 'App\\'.$closure[0];
            $method = $closure[1];
            $class = new $className;
            return [$class,$method];
        }
        else {
            return $closure;
        }
    }
}
