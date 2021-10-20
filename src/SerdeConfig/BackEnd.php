<?php

declare(strict_types=1);

namespace Crell\SerializerTest\SerdeConfig;

use Crell\Serde\Field;

class BackEnd
{
    public function __construct(
        public readonly bool $debug,
        public readonly string $explicitADmode,
        public readonly string $installToolPassword,
        #[Field(serializedName: 'passwordHashing')]
        public readonly PasswordHashing $passwords,
    ) {}
}