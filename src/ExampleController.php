<?php

declare(strict_types=1);

class ExampleController
{
    #[Route('/home', name: 'home')]
    public function home(): void
    {
        // render home page
    }

    public function noAttributes(): void
    {
        // do nothing
    }

    private function home2(): void
    {
        // private method
    }
}


