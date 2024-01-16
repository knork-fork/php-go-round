<?php

declare(strict_types=1);

class ExampleController
{
    #[Route('/home', name: 'home')]
    public function home(): void
    {
        // render home page
        echo 'this is home.';
    }

    public function noAttributes(): void
    {
        // do nothing
    }
}


