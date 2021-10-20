<?php

declare(strict_types=1);

namespace Crell\SerializerTest\SerdeConfig;

use Crell\Serde\Field;

class ScOptions implements \ArrayAccess
{
    public function __construct(
        #[Field(flatten: true)]
        public readonly array $options = []
    ) {}

    public function offsetExists($offset): bool
    {
        return array_key_exists($offset, $this->options);
    }

    public function offsetGet($offset): mixed
    {
        return $this->options[$offset];
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