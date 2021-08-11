<?php

declare(strict_types=1);

namespace Crell\SerializerTest\Config;

use Symfony\Component\Serializer\Annotation\SerializedName;

class BackEnd
{
    public bool $debug;
    public string $explicitADmode;
    public string $installToolPassword;
    #[SerializedName('passwordHashing')]
    public PasswordHashing $passwords;

}