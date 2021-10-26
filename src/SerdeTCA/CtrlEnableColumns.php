<?php

declare(strict_types=1);

namespace Crell\SerializerTest\SerdeTCA;

use Crell\Serde\Field;
use Crell\Serde\Renaming\Cases;

class CtrlEnableColumns
{
    public function __construct(
        public readonly string $disabled = '',
        #[Field(serializedName: 'starttime')]
        public readonly string $startTime = '',
        #[Field(serializedName: 'endtime')]
        public readonly string $endTime = '',
        #[Field(renameWith: Cases::snake_case)]
        public readonly string $feGroup = '',
    ) {
    }
}