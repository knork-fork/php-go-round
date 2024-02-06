<?php declare(strict_types=1);

namespace App\Controller;

use PhpGoRound\Endpoint;
use PhpGoRound\Route;

#[Endpoint]
class ExampleController
{
    #[Route('/home', name: 'home')]
    public function home(): void
    {
        // render home page
        echo 'this is home.';
    }

    #[Route('/params_test', name: 'params test')]
    public function paramsTest(string $text, bool $check, int $number): void
    {
        echo 'params dumped to logs/php_errors.log';

        error_log(sprintf(
            'params: text - %s, check - %s, number - %d, var_export: %s',
            $text,
            $check,
            $number,
            var_export([$text, $check, $number], true)
        ));
    }

    #[Route('/key_test', name: 'key test')]
    public function keyTest(string $key): void
    {
        echo 'key dumped to logs/php_errors.log';

        error_log('key: ' . $key);
    }

    public function noAttributes(): void
    {
        // do nothing
    }
}


