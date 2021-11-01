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

class TableInterface
{
    public function __construct(
        public readonly int $maxDBListItems = 20,
        public readonly int $maxSingleDBListItems = 100,
    ) {}
}

class DisplayType
{
    public function __construct(
        // This may be wrong, since $showitem also gets used
        // to define the layout structure of pallets. Which is nutty.
        // The alternative is to make this protected and provide
        // methods that disassemble it into the various options.
        #[SequenceField(implodeOn: ',')]
        public readonly array $showitem = [],
    ) {}
}
