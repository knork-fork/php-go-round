<?php

// to-do: add autoloader
require 'Route.php';
require 'ExampleController.php';
require 'System/RouteGenerator.php';
require 'System/Request.php';

$request = Request::loadRequest($argv);

// somehow get a list of controllers before this step
$controllerClasses = [];
$controllerClasses[] = new ReflectionClass('ExampleController');

// to-do: this should be saved/cached somewhere
$routes = RouteGenerator::generateRoutes($controllerClasses);

foreach ($routes as $route) {
    if ($route->url == $request->route) {
        $instance = $route->class->newInstance();
        $route->method->invoke($instance, null);

        die();
    }
}

die('error 404');