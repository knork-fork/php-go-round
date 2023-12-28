<?php

declare(strict_types=1);

#[Attribute]
class Route
{
    public function __construct(
        public string $url,
        public string $name
    ) {}
}