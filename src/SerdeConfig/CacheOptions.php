<?php

declare(strict_types=1);

namespace Crell\SerializerTest\SerdeConfig;

class CacheOptions
{
    public function __construct(
        public readonly bool $compression = false,
        public readonly int $defaultLifetime = 0,
    ) {}
}