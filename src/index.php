<?php

// to-do: add autoloader
require 'Route.php';
require 'ExampleController.php';
require 'System/RouteGenerator.php';

// somehow get a list of controllers before this step
$controllerClasses = [];
$controllerClasses[] = new ReflectionClass('ExampleController');

// to-do: this should be saved/cached somewhere
RouteGenerator::generateRoutes($controllerClasses);

