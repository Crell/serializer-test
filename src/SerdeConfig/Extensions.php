<?php

declare(strict_types=1);

namespace Crell\SerializerTest\SerdeConfig;

class Extensions
{
    public function __construct(
        public readonly array $extensions = [],
    ) {}
}
