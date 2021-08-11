<?php

declare(strict_types=1);

namespace Crell\SerializerTest\Config;

class CacheOptions
{
    public function __construct(public bool $compression = false) {}
}