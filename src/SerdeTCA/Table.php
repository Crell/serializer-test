<?php

declare(strict_types=1);

namespace Crell\SerializerTest\SerdeTCA;

use Crell\Serde\DictionaryField;
use Crell\Serde\Field;

class Table
{
    public function __construct(
        public readonly Ctrl $ctrl,
        #[DictionaryField(arrayType: Column::class)]
        /** @var Column[] */
        public readonly array $columns,
    ) {
    }
}