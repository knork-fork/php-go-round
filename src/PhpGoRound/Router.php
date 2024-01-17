<?php declare(strict_types=1);

namespace PhpGoRound;

use App\Exception\NotFoundException;
use Exception;
use ReflectionClass;

class Router
{
    public static function callRouteByUrl(string $url): void
    {
        // Find all classes with Endpoint attribute
        $controllers = [];
        foreach (self::getControllers() as $class) {
            $reflectionClass = new ReflectionClass($class);
            if (count($reflectionClass->getAttributes(Endpoint::class)) > 0) {
                $controllers[] = $reflectionClass;
            }
        }

        if (count($controllers) === 0) {
            throw new NotFoundException();
        }

        // Find method with route attribute that matches input url
        foreach ($controllers as $controller) {
            $methods = $controller->getMethods();
            foreach ($methods as $method) {
                if (!$method->isPublic()) {
                    continue;
                }

                $routeAttributes = $method->getAttributes(Route::class);
                foreach ($routeAttributes as $routeAttribute) {
                    $routeInstance = $routeAttribute->newInstance();
 
                    if ($routeInstance->url === $url) {
                        $method->invoke(
                            $controller->newInstance(),
                            null
                        );

                        return;
                    }
                }
            }
        }

        throw new NotFoundException();
    }

    /**
     * @return class-string[] 
     */
    private static function getControllers(): array
    {
        // TO-DO: this only works for files in src/Controller, not subdirectories
        // TO-DO: also this should be cached
        $controllers = [];

        $files = scandir('/app/src/Controller');
        if ($files === false) {
            throw new Exception('Could not scan directory /app/src/Controller');
        }

        $files = array_filter($files, fn($file) => !in_array($file, ['.', '..']));
        foreach ($files as $file) {
            /** @var class-string $class */
            $class = 'App\\Controller\\' . rtrim($file, '.php');
            $controllers[] = $class;
        }

        return $controllers;
    }
}