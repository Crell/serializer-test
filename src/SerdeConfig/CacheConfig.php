<?php

declare(strict_types=1);

namespace Crell\SerializerTest\SerdeConfig;

use Symfony\Component\Serializer\Annotation\SerializedName;

class CacheConfig
{
    public function __construct(
        public readonly string $backend = '',
        #[SerializedName('options')]
        public readonly CacheOptions $options = new CacheOptions(),
    ) {}
}
