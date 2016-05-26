<?php

$basePath = realpath(__DIR__ . '/..') . '/';
$environment = strtolower(getenv('APPLICATION_ENV'));
if (!$environment) {
    echo 'Please check your project configuration!';
    exit;
}

include($basePath . '/vendor/autoload.php');
$configs = include($basePath . '/configs/env/' . $environment . '.php');
$configs['environment'] = $environment;
return $configs;
