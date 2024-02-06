<?php

use PhpGoRound\Request;
use PhpGoRound\Router;

spl_autoload_register(function ($className) {
    if (str_starts_with($className, 'App\\') === true) {
        $className = substr($className, strlen('App\\'));
    }
    $file = '/app/src/' . str_replace('\\', '/', $className) . '.php';
    if (file_exists($file)) {
        require $file;
    } else {
        throw new Exception('Class not found: ' . $className . ' (' . $file . ')');
    }
});


$request = Request::loadRequest($argv);

// Ignore /favicon.ico, for now
if ($request->route === '/favicon.ico') {
    die();
}

$router = new Router($request);
$router->callRouteByUrl();

die();
