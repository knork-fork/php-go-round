<?php

declare(strict_types=1);

class Request
{
    public function __construct(
        public string $route
    ) {}

    public static function loadRequest(array $args): Request
    {
        // to-do: there will probably be way more than 2 arguments
        if (count($args) != 2) {
            throw new Exception('Something very wrong');
        }
        $route = $args[1];

        return new Request($route);
    }
}