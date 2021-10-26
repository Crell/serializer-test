<?php

declare(strict_types=1);

namespace Crell\SerializerTest\SerdeTCA;

class Table
{
    public function __construct(
        public Ctrl $ctrl,
    ) {}
}