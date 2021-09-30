<?php

declare(strict_types=1);

namespace Crell\SerializerTest\Config;

use Crell\Serde\Field;
use Symfony\Component\Serializer\Annotation\SerializedName;

class BackEnd
{
    public bool $debug;
    public string $explicitADmode;
    public string $installToolPassword;
    #[SerializedName('passwordHashing')]
    #[Field(serializedName: 'passwordHashing')]
    public PasswordHashing $passwords;

}