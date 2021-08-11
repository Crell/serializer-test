<?php

declare(strict_types=1);

namespace Crell\SerializerTest\Config;

use Symfony\Component\Serializer\Annotation\SerializedName;

class FrontEnd
{
    public bool $debug;
    public bool $disableNoCacheParameter;
    #[SerializedName('passwordHashing')]
    public PasswordHashing $passwords;

}