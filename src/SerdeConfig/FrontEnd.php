<?php

declare(strict_types=1);

namespace Crell\SerializerTest\SerdeConfig;

use Crell\Serde\Field;

class FrontEnd
{
    public function __construct(
        public readonly bool $debug,
        public readonly bool $disableNoCacheParameter,
        #[Field(serializedName: 'passwordHashing')]
        public readonly PasswordHashing $passwords,

    ) {}
}