<?php

declare(strict_types=1);

namespace Crell\SerializerTest\SerdeConfig;

use Crell\Serde\Field;

class Caching implements \ArrayAccess
{
    public function __construct(
        /** @var array<string, CacheConfig> */
        #[Field(arrayType: CacheConfig::class)]
        public readonly array $cacheConfigurations = [],
    ) {}

    public function offsetExists($offset): bool
    {
        return array_key_exists($offset, $this->cacheConfigurations);
    }

    public function offsetGet($offset): CacheConfig
    {
        return $this->cacheConfigurations[$offset];
    }

    public function offsetSet($offset, $value): void
    {
        throw new \RuntimeException('Cannot modify config at runtime');
    }

    public function offsetUnset($offset): void
    {
        throw new \RuntimeException('Cannot modify config at runtime');
    }
}
