<?php

namespace Jewelry\Core;

use Jewelry\Controllers\ErrorController;
use Jewelry\Controllers\CustomerController;

class Router{
    private $routeMap;
    private static $regexPatterns = [
        'number' => '\d+',
        'string' => '\w'
    ];

    public function __construct(){
        $json = file_get_contents(__DIR__ . '/../../config/routes.json');
        $this->routeMap = json_decode($json, true);
    }

    public function route(Request $request):string{
        $path = $request->getPath();

        foreach($this->routeMap as $route => $info){
            $regexRoute = $this->getRegexRoute($route, $info);
            if(preg_match("@^/$regexRoute$@",$path)){
                return $this->ExecuteController($route, $path, $info, $request);
            }
        }
        $errorController = new ErrorController($request)
        return $errorController->notFound():
    }

    private function getRegaxRoute(string $route, array $info): string{
        if(isset($info['params'])){
            foreach($info['params'] as $name => $type){
                $route = str_replace(":" . $name, self::$regexPatterns[$type], $route);
            }
        }
        return $route;
    }

    private function extractParams(string $route, string $path):array{//route dobijamo iz composer.json a path je putanja koju mo dobili
        $params = [];
        $pathParts = explode('/', $path);
        $routeParts = explode('/', $route);
        foreach($routeParts as $key => $routePart){
            if(strpos($routePart, ':') === 0){
                $name = substr($routePart,1);
                $params[$name] = $pathParts[$key + 1];
            }
        }
        return $params;
    }

    private function executeController(srting $route, string $path, array $info, Request $request):string{
        $controllerName = '\Jewelry\Controllers\\' . $info['controller'] . 'Controller';
        $controller = new $controllerName($request);

        if(isset($info['login']) && $info['login']){
            if($request->getCookies()->has('user')){
                $customerId = $request->getCookies()->get('user');
                $controller->setCustomerId($customerId);
            }else{
                $errorController = new CustomerController($request);
                return $errorController->login();
            }
        }
        $params = $this->extractParams($route, $path);
        return call_user_func_array([$controller, $info['method']], $params);
    }
}