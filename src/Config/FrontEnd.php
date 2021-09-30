<?php

declare(strict_types=1);

namespace Crell\SerializerTest\Config;

use Crell\Serde\Field;
use Symfony\Component\Serializer\Annotation\SerializedName;

class FrontEnd
{
    public bool $debug;
    public bool $disableNoCacheParameter;
    #[SerializedName('passwordHashing')]
    #[Field(serializedName: 'passwordHashing')]
    public PasswordHashing $passwords;

}