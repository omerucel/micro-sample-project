<?php

namespace Project;

use OU\Application\Http\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Controller
{
    /**
     * @param Application $app
     * @param Request $request
     * @param Response $response
     * @param array $params
     * @return Response
     */
    public function handle(Application $app, Request $request, Response $response, array $params = array())
    {
        $response->setContent('Name: ' . $params['name']);
        return $response;
    }
}
