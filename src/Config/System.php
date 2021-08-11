<?php

declare(strict_types=1);

namespace Crell\SerializerTest\Config;

class System
{
    public function __construct(
        public ?Caching $caching = null,
        public string $devIPmask = '',
        public int $displayErrors = 0,
        public string $encryptionKey = '',
        public int $exceptionalErrors = 0,
        public string $sitename = '',
        public array $systemMaintainers = [],
        private array $features = [],
    )
    {
        $this->caching ??= new Caching();
    }

    public function isFeatureEnabled(string $feature): bool
    {
        return $this->features[$feature] ?? false;
    }
}

