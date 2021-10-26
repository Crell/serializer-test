<?php

declare(strict_types=1);

namespace Crell\SerializerTest\SerdeTCA;

class CtrlSecurity
{
    public function __construct(
        public readonly bool $ignoreWebMountRestriction = true,
        public readonly bool $ignoreRootLevelRestriction = true,
    ) {
    }
}