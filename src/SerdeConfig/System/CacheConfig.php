<?php

declare(strict_types=1);

namespace Crell\SerializerTest\SerdeConfig\System;

use Symfony\Component\Serializer\Annotation\SerializedName;

class CacheConfig
{
    public function __construct(
        public readonly string $frontend = \TYPO3\CMS\Core\Cache\Frontend\PhpFrontend::class,
        public readonly string $backend = \TYPO3\CMS\Core\Cache\Backend\SimpleFileBackend::class,
        #[SerializedName('options')]
        public readonly CacheOptions $options = new CacheOptions(),
        public readonly array $groups = [],
    ) {}
}
