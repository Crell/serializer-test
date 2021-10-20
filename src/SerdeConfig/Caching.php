<?php

declare(strict_types=1);

namespace Crell\SerializerTest\SerdeConfig;

use Crell\Serde\Field;

class Caching
{
    public function __construct(
        /** @var array<string, CacheConfig> */
        #[Field(arrayType: CacheConfig::class)]
        public readonly array $cacheConfigurations = [],
    ) {}
}