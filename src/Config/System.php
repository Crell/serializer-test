<?php

declare(strict_types=1);

namespace Crell\SerializerTest\Config;

class System
{
    /** @var CacheConfig[] */
    public array $cacheConfigurations = [];

    public function __construct(
        array $cacheConfigurations = [],
        public string $devIPmask = '',
        public int $displayErrors = 0,
        public string $encryptionKey = '',
        public int $exceptionalErrors = 0,
        public string $sitename = '',
        public array $systemMaintainers = [],
        private array $features = [],
    )
    {
        $this->cacheConfigurations = $cacheConfigurations;
    }

    public function isFeatureEnabled(string $feature): bool
    {
        return $this->features[$feature] ?? false;
    }
}
