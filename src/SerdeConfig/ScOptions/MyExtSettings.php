<?php

declare(strict_types=1);

namespace Crell\SerializerTest\SerdeConfig\ScOptions;

class MyExtSettings
{
    public function __construct(
        public readonly string $firstName = '',
        public readonly string $lastName = '',
    ) {}
}