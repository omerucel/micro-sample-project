<?php

namespace OU\Application\Http;

use FastRoute\RouteCollector;
use OU\DI;

class Dispatcher
{
    /**
     * @var DI
     */
    protected $httpRequest;

    /**
     * @var \stdClass
     */
    protected $currentRoute;

    /**
     * @var array
     */
    protected $routes = array();

    /**
     * @param $httpMethod
     * @param $uri
     * @return null|\stdClass
     */
    public function dispatch($httpMethod, $uri)
    {
        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }
        $uri = rawurldecode($uri);
        $routes = $this->getRoutes();
        /**
         * @var \FastRoute\Dispatcher $dispatcher
         */
        $dispatcher = \FastRoute\simpleDispatcher(function (RouteCollector $r) use ($routes) {
            foreach ($routes as $route) {
                $r->addRoute($route[0], $route[1], $route[2]);
            }
        });
        $routeInfo = $dispatcher->dispatch($httpMethod, $uri);
        if ($routeInfo[0] == \FastRoute\Dispatcher::FOUND) {
            $routeParams = $routeInfo[2];
            if (is_array($routeParams) == false) {
                $routeParams = array();
            }
            $this->currentRoute = new \stdClass();
            $this->currentRoute->handler = $routeInfo[1];
            $this->currentRoute->params = $routeParams;
            return $this->currentRoute;
        } else {
            return null;
        }
    }

    /**
     * @return array
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * @param $httpMethod
     * @param $path
     * @param $handler
     */
    public function addRoute($httpMethod, $path, $handler)
    {
        $this->routes[] = array($httpMethod, $path, $handler);
    }

    /**
     * @return \stdClass
     */
    public function getCurrentRoute()
    {
        return $this->currentRoute;
    }
}
