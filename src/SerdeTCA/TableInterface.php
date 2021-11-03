<?php

declare(strict_types=1);

namespace Crell\SerializerTest\SerdeTCA;

/**
 * This is the interface section of the Table definition, not an interface for a table object.
 */
class TableInterface
{
    public function __construct(
        public readonly int $maxDBListItems = 20,
        public readonly int $maxSingleDBListItems = 100,
    ) {
    }
}