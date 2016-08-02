<?php

namespace OU\Application\Http;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Application extends \OU\Application\Application
{
    /**
     * @var Dispatcher
     */
    protected $dispatcher;

    /**
     * @var Request
     */
    protected $httpRequest;

    /**
     * @var Response
     */
    protected $httpResponse;

    /**
     * @var array
     */
    protected $preHandlers = array();

    /**
     * @var array
     */
    protected $postHandlers = array();

    /**
     * @var null
     */
    protected $notFoundHandler = null;

    /**
     * @param array $configs
     */
    public function __construct(array $configs = array())
    {
        parent::__construct($configs);
        $this->dispatcher = new Dispatcher($this->getDi());
        $this->httpRequest = Request::createFromGlobals();
        $this->httpResponse = new Response();
    }

    /**
     * @param $httpMethod
     * @param $path
     * @param $handler
     */
    public function addRoute($httpMethod, $path, $handler)
    {
        $this->getDispatcher()->addRoute($httpMethod, $path, $handler);
    }

    /**
     * @param $callback
     */
    public function addPreHandler($callback)
    {
        $this->preHandlers[] = $callback;
    }

    /**
     * @param $callback
     */
    public function addPostHandler($callback)
    {
        $this->postHandlers[] = $callback;
    }

    /**
     * @param \stdClass|null $route
     * @throws \Exception
     */
    public function dispatch(\stdClass $route = null)
    {
        if ($route == null) {
            $route = $this->getDispatcher()->dispatch(
                $this->getHttpRequest()->getMethod(),
                $this->getHttpRequest()->getRequestUri()
            );
            if ($route == null) {
                $route = new \stdClass();
                $route->handler = $this->getNotFoundHandler();
                $route->params = array();
            }
        }
        $response = $this->sendResponse($route);
        $response->send();
    }

    /**
     * @param \stdClass $route
     * @return Response
     * @throws \Exception
     */
    protected function sendResponse(\stdClass $route)
    {
        $params = $this->getHandlerParams();
        array_push($params, $route->params);
        $response = null;
        foreach ($this->preHandlers as $handler) {
            $response = call_user_func_array($this->getHandler($handler), $params);
            if ($response instanceof Response) {
                return $response;
            }
        }
        $response = call_user_func_array($this->getHandler($route->handler), $params);
        if ($response instanceof Response) {
            return $response;
        }
        foreach ($this->postHandlers as $handler) {
            $response = call_user_func_array($this->getHandler($handler), $params);
            if ($response instanceof Response) {
                return $response;
            }
        }
        throw new \Exception();
    }

    /**
     * @param $definition
     * @return array
     * @throws \Exception
     */
    protected function getHandler($definition)
    {
        if (is_string($definition)) {
            $object = new $definition();
            return array($object, 'handle');
        } elseif (is_callable($definition)) {
            return $definition;
        } else {
            throw new \Exception();
        }
    }

    /**
     * @return null
     */
    public function getNotFoundHandler()
    {
        if ($this->notFoundHandler == null) {
            $this->notFoundHandler = function (Application $app, Request $request, Response $response) {
                $response->setStatusCode(404);
                $response->setContent('Not found');
                return $response;
            };
        }
        return $this->notFoundHandler;
    }

    /**
     * @param null $notFoundHandler
     */
    public function setNotFoundHandler($notFoundHandler)
    {
        $this->notFoundHandler = $notFoundHandler;
    }

    /**
     * @return array
     */
    public function getHandlerParams()
    {
        return array($this, $this->httpRequest, $this->httpResponse);
    }

    /**
     * @return Dispatcher
     */
    public function getDispatcher()
    {
        return $this->dispatcher;
    }

    /**
     * @return Request
     */
    public function getHttpRequest()
    {
        return $this->httpRequest;
    }

    /**
     * @return Response
     */
    public function getHttpResponse()
    {
        return $this->httpResponse;
    }
}
