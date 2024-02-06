<?php declare(strict_types=1);

namespace PhpGoRound;

use Exception;

class Request
{
    /**
     * @param array<string, int|string|bool|null> $params 
     */
    public function __construct(
        public string $route,
        //public string $method = 'GET', // to-do, always GET for now
        //public string $body = '', // to-do
        public array $params,
    ) {}

    /**
     * @param string[] $args 
     */
    public static function loadRequest(array $args): Request
    {
        // to-do: handle better
        if (count($args) != 3) {
            throw new Exception('Something very wrong');
        }

        $route = $args[1];

        $queryString = $args[2] !== '' ? $args[2] : null;
        if ($queryString !== null) {
            $params = [];
            $pairs = explode('&', $queryString);
            foreach ($pairs as $pair) {
                $keyValue = explode('=', $pair);
                $params[$keyValue[0]] = self::getValueFromString($keyValue[1]);
            }
        } else {
            $params = [];
        }

        return new Request($route, $params);
    }

    private static function getValueFromString(string $param): int|string|bool|null
    {
        if ($param === '') {
            return null;
        }

        if (in_array($param, ['true', 'false'])) {
            return $param === 'true';
        }

        if (filter_var($param, FILTER_VALIDATE_INT) !== false) {
            return (int) $param;
        }

        return $param;
    }
}