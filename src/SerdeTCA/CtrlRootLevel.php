<?php

declare(strict_types=1);

namespace Crell\SerializerTest\SerdeTCA;

enum CtrlRootLevel: int
{
    case PageTreeOnly = 0;
    case RootOnly = 1;
    case PageTreeOrRoot = -1;

    public function inPageTree(): bool
    {
        return in_array($this, [self::PageTreeOnly, self::PageTreeOrRoot]);
    }

    public function inRoot(): bool
    {
        return in_array($this, [self::RootOnly, self::PageTreeOrRoot]);
    }
}