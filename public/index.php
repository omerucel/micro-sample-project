<?php

namespace {

    use OU\Application\Http\Application;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;

    $configs = include(realpath(__DIR__ . '/../') . '/configs/bootstrap.php');
    $app = new Application($configs);
    $app->getDi()->setSharedService('logger_helper', 'OU\Application\Service\LoggerHelperService');
    $app->getDi()->setSharedService('pdo', 'OU\Application\Service\PdoService');
    $app->getDi()->setSharedService('twig', 'OU\Application\Service\TwigService');

    $app->setExceptionHandler(function (Application $app, Request $request, Response $response, $exception) {
        $app->getDi()->get('logger_helper')->getLogger()->error($exception);
        $response->setStatusCode(500);
        $response->setContent('An error occurred');
        $response->send();
    });

    $app->setFatalErrorHandler(function (Application $app, Request $request, Response $response, $errorInfo) {
        $app->getDi()->get('logger_helper')->getLogger()->emergency('Fatal Error', $errorInfo);
        $response->setStatusCode(500);
        $response->setContent('Internal Server Error');
        $response->send();
    });

    $app->addPreHandler(function (Application $app, Request $request, Response $response) {
        $response->headers->set('X-App-Request-Id', $app->getDi()->get('config')->req_id);
    });

    $app->setNotFoundHandler(function (Application $app, Request $request, Response $response) {
        $app->getDi()->get('logger_helper')->getLogger()->info(
            'Page not found',
            array(
                'method' => $request->getMethod(),
                'params' => $_REQUEST,
                'ip' => $request->getClientIp()
            )
        );
        $response->headers->set('Content-Type', 'text/plain; charset=utf-8');
        $response->setStatusCode(404)
            ->setContent('Not found');
        return $response;
    });

    $app->addRoute('GET', '/', function (Application $app, Request $request, Response $response) {
        /**
         * @var \Twig_Environment $twig
         */
        $twig = $app->getDi()->get('twig');
        $content = $twig->render('hello.twig');
        $response->headers->set('Content-Type', 'text/html; charset=utf-8');
        $response->setStatusCode(200)
            ->setContent($content);
        return $response;
    });
    $app->addRoute('GET', '/users/{name}', 'Project\Controller');
    $app->dispatch();
}
