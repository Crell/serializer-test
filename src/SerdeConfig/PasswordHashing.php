<?php

declare(strict_types=1);

namespace Crell\SerializerTest\SerdeConfig;

class PasswordHashing
{
    public function __construct(
        public readonly string $className,
        public readonly array $options,
    ) {}
}