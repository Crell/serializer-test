<?php

declare(strict_types=1);

namespace Crell\SerializerTest\Config;

class System
{
    public Caching $caching;

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
        $this->caching = new Caching();
        $this->caching->cacheConfigurations = $cacheConfigurations;
    }

    public function isFeatureEnabled(string $feature): bool
    {
        return $this->features[$feature] ?? false;
    }
}

class Caching
{
    /** @var array<string, CacheConfig> */
    public array $cacheConfigurations = [];
}
