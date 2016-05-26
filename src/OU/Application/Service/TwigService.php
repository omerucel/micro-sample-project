<?php

namespace OU\Application\Service;

use OU\DI;
use OU\Service;

class TwigService implements Service
{
    /**
     * @param DI $di
     * @return \Twig_Environment
     * @throws \Exception
     */
    public function getService(DI $di)
    {
        $config = $di->get('config');
        $loader = new \Twig_Loader_Filesystem($config->twig->templates_path);
        $twig = new \Twig_Environment($loader, json_decode(json_encode($config->twig), true));
        return $twig;
    }
}
