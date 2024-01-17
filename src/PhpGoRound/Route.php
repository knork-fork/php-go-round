<?php declare(strict_types=1);

namespace PhpGoRound;

use Attribute;

#[Attribute]
class Route
{
    public function __construct(
        public string $url,
        public string $name
    ) {}
}