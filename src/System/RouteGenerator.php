<?php

declare(strict_types=1);

class RouteGenerator
{
    /**
     * @param ReflectionClass[] $controllerClasses
     * @return Route[]
     */
    public static function generateRoutes(array $controllerClasses): array
    {
        $routes = [];

        foreach ($controllerClasses as $controllerClass) {
            $methods = $controllerClass->getMethods();
            foreach ($methods as $method) {
                if (!$method->isPublic()) {
                    continue;
                }

                $routeAttributes = $method->getAttributes(Route::class);
                foreach ($routeAttributes as $routeAttribute) {
                    $routeInstance = $routeAttribute->newInstance();
                    $routeInstance->class = $controllerClass;
                    $routeInstance->method = $method;
                    /*echo sprintf(
                        'url %s for %s::%s()',
                        $routeInstance->url,
                        $controllerClass->getName(),
                        $method->getName()
                    );*/
                    $routes[] = $routeInstance;
                }
            }
        }


        return $routes;
    }
}