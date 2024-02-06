<?php declare(strict_types=1);

namespace App\Exception;

class BadRequestException extends \Exception
{
    public function __construct(
        string $message = 'Bad Request',
        int $code = 400,
        \Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}