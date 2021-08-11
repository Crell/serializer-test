<?php

declare(strict_types=1);

namespace Crell\SerializerTest\Config;

use Symfony\Component\Serializer\Annotation\SerializedName;

class CacheConfig
{
    public function __construct(
        public string $backend = '',
        #[SerializedName('options')]
        public ?CacheOptions $options = null,
    ) {
        // @todo use new-in-initializer here instead so null is impossible.
        $this->options ??= new CacheOptions();
    }
}
