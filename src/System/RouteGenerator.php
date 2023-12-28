<?php

declare(strict_types=1);

class RouteGenerator
{
    /**
     * @param ReflectionClass[] $controllerClasses
     */
    public static function generateRoutes(array $controllerClasses): void
    {
        foreach ($controllerClasses as $controllerClass) {
            $methods = $controllerClass->getMethods();
            foreach ($methods as $method) {
                if (!$method->isPublic()) {
                    continue;
                }

                $routeAttributes = $method->getAttributes(Route::class);
                foreach ($routeAttributes as $routeAttribute) {
                    $routeInstance = $routeAttribute->newInstance();
                    echo sprintf(
                        'url %s for %s::%s()',
                        $routeInstance->url,
                        $controllerClass->getName(),
                        $method->getName()
                    );
                }
            }
        }
    }
}