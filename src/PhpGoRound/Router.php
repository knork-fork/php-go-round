<?php declare(strict_types=1);

namespace PhpGoRound;

use App\Exception\BadRequestException;
use App\Exception\NotFoundException;
use Exception;
use ReflectionClass;
use ReflectionMethod;
use ReflectionNamedType;

class Router
{
    public function __construct(
        private Request $request
    )
    {}

    public function callRouteByUrl(): void
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
 
                    if ($routeInstance->url === $this->request->route) {
                        $methodParameters = $this->getMethodParameters($method);
                        $this->checkRequestParamsMatchMethodParams($methodParameters);
                        
                        if ($method->getNumberOfParameters() > 0) {
                            $method->invokeArgs(
                                $controller->newInstance(),
                                $this->request->params
                            );
                        } else {
                            $method->invoke(
                                $controller->newInstance(),
                                null
                            );
                        }

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
    private function getControllers(): array
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

    /**
     * @return array<string, string>
     */
    private function getMethodParameters(ReflectionMethod $method): array
    {
        $parameters = [];
        foreach ($method->getParameters() as $parameter) {
            $type = $parameter->getType();
            if ($type instanceof ReflectionNamedType) {
                $typeName = $type->getName();
            } else {
                $typeName = 'mixed';
            }
            $parameters[$parameter->getName()] = $typeName;
        }

        return $parameters;
    }

    /**
     * @param array array<string, string>
     */
    private function checkRequestParamsMatchMethodParams(array $methodParams): void
    {     
        if (count($methodParams) !== count($this->request->params)) {
            $this->throwParamsDoNotMatchException();
        }

        // gettype returns 'integer' instead of 'int', 'boolean' instead of 'bool', etc.
        $typeMap = [
            'boolean' => 'bool',
            'integer' => 'int',
        ];

        foreach ($methodParams as $name => $type) {
            if (!array_key_exists($name, $this->request->params)) {
                $this->throwParamsDoNotMatchException();
            }

            $paramType = gettype($this->request->params[$name]);
            $paramType = $typeMap[$paramType] ?? $paramType;

            if ($paramType !== $type) {
                var_dump($paramType);
                var_dump($type);
                $this->throwParamsDoNotMatchException();
            }
        }  
    }

    private function throwParamsDoNotMatchException(): void
    {
        throw new BadRequestException('Request params do not match method parameters');
    }
}
