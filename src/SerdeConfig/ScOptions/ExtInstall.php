<?php

declare(strict_types=1);

namespace Crell\SerializerTest\SerdeConfig\ScOptions;

class ExtInstall
{
    public function __construct(
        public readonly array $update = [],
    ) {}
}
