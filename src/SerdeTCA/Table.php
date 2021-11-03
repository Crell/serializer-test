<?php

declare(strict_types=1);

namespace Crell\SerializerTest\SerdeTCA;

use Crell\Serde\DictionaryField;
use Crell\Serde\SequenceField;

class Table
{
    public function __construct(
        public readonly Ctrl $ctrl,
        #[DictionaryField(arrayType: Column::class)]
        /** @var Column[] */
        public readonly array $columns,
        public readonly TableInterface $interface = new TableInterface(),
        #[DictionaryField(arrayType: DisplayType::class)]
        /** @var DisplayType[] */
        public readonly array $types = [],
    ) {
    }
}
