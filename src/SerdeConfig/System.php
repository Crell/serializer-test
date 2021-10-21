<?php

declare(strict_types=1);

namespace Crell\SerializerTest\SerdeConfig;

use Crell\SerializerTest\SerdeConfig\System\Caching;

class System
{
    public function __construct(
        public readonly ?Caching $caching = new Caching(),
        public readonly string $devIPmask = '',
        public readonly int $displayErrors = 0,
        public readonly string $encryptionKey = '',
        public readonly int $exceptionalErrors = 0,
        public readonly string $sitename = '',
        public readonly array $systemMaintainers = [],
        private readonly array $features = [],
    ) {}

    public function isFeatureEnabled(string $feature): bool
    {
        return $this->features[$feature] ?? false;
    }
}

