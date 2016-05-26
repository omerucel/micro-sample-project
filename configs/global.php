<?php

$basePath = realpath(__DIR__ . '/../');
error_reporting(E_ALL);
ini_set('display_errors', false);
ini_set('error_log', $basePath . '/logs/php_error.log');
date_default_timezone_set('UTC');

/**
 * Init
 */
$configs = array(
    'base_path' => $basePath,
    'tmp_path' => $basePath . '/tmp',
    'req_id' => uniqid('REQ-' . gethostname()),
    'php_bin' => '/usr/bin/php'
);

/**
 * PDO Service Configs
 */
$configs['pdo'] = array();
$configs['pdo']['dsn'] = 'mysql:host=mysql;dbname=sample;charset=utf8';
$configs['pdo']['hostname'] = 'mysql';
$configs['pdo']['database'] = 'sample';
$configs['pdo']['username'] = 'root';
$configs['pdo']['password'] = '';

/**
 * Logger
 */
$configs['logger'] = array();
$configs['logger']['default_name'] = 'default';
$configs['logger']['default_path'] = realpath($basePath . '/logs');
$configs['logger']['default_level'] = \Monolog\Logger::INFO;
/**
 * supports different path and level for log name
 * $configs['logger']['app'] = array();
 * $configs['logger']['app']['path'] = realpath(BASE_PATH . '/logs');
 * $configs['logger']['app']['level'] = \Monolog\Logger::DEBUG;
 * $di->getLogger('app')->info('foo bar');
 */

/**
 * Twig
 */
$configs['twig'] = array();
$configs['twig']['templates_path'] = $basePath . '/templates';
$configs['twig']['cache'] = $basePath . '/tmp/twig';
$configs['twig']['auto_reload'] = true;

return $configs;
