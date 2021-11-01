<?php

declare(strict_types=1);

namespace Crell\SerializerTest\SerdeTCA;

use Crell\Serde\DictionaryField;

class Table
{
    public function __construct(
        public readonly Ctrl $ctrl,
        #[DictionaryField(arrayType: Column::class)]
        /** @var Column[] */
        public readonly array $columns,
        public readonly TableInterface $interface = new TableInterface(),
    ) {
    }
}

class TableInterface
{
    public function __construct(
        public readonly int $maxDBListItems = 20,
        public readonly int $maxSingleDBListItems = 100,
    ) {}
}
