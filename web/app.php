<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Debug\Debug;

// If you don't want to setup permissions the proper way, just uncomment the following PHP line
// read http://symfony.com/doc/current/book/installation.html#configuration-and-setup for more information
umask(0000);

$loader = require_once __DIR__.'/../app/bootstrap.php.cache';
Debug::enable();

require_once __DIR__.'/../app/AppKernel.php';

$kernel = new AppKernel('dev', true);
$kernel->loadClassCache();
$request = Request::createFromGlobals();

// Trust all requests (assuming they can only come from the load balancer with no direct access to instances)
Request::setTrustedProxies(array($request->server->get('REMOTE_ADDR')));

$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
