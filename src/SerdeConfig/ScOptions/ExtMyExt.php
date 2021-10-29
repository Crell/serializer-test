<?php

declare(strict_types=1);

namespace Crell\SerializerTest\SerdeConfig\ScOptions;

use Crell\Serde\DictionaryField;
use Crell\Serde\Field;

class ExtMyExt
{
    public function __construct(
        public readonly int $level = 2,
        public readonly string $defaultValue = 'hello world',
        #[DictionaryField(arrayType: MyExtSettings::class)]
        public readonly array $settings = [],
    ) {}
}
