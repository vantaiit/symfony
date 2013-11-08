<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Debug\Debug;

// If you don't want to setup permissions the proper way, just uncomment the following PHP line
// read http://symfony.com/doc/current/book/installation.html#configuration-and-setup for more information
//umask(0000);

// This check prevents access to debug front controllers that are deployed by accident to production servers.
// Feel free to remove this, extend it, or make something more sophisticated.
$allowsIp = array('127.0.0.1', 'fe80::1', '::1', '118.69.52.186', '118.69.183.83', '115.78.131.28', '207.223.240.187', '207.223.240.188');
if (isset($_SERVER['HTTP_CLIENT_IP'])
    || isset($_SERVER['HTTP_X_FORWARDED_FOR'])
    || !in_array(@$_SERVER['REMOTE_ADDR'], $allowsIp)
) {
    header('HTTP/1.0 403 Forbidden');
    exit('You are not allowed to access this file. Check '.basename(__FILE__).' for more information.');
}

$loader = require_once __DIR__.'/../app/bootstrap.php.cache';
Debug::enable();

Request::enableHttpMethodParameterOverride();
$request = Request::createFromGlobals();

if($request->get('cclear') == 1) {
    $filesystem = new \Symfony\Component\Filesystem\Filesystem();
    $cacheDir = realpath(__DIR__ . '/../app/cache/prod');
    if ($cacheDir) {
        $filesystem->remove($cacheDir);
        die();
    }
}

require_once __DIR__.'/../app/AppKernel.php';

$kernel = new AppKernel('dev', true);
$kernel->loadClassCache();

$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
