<?php

declare(strict_types=1);

namespace Crell\SerializerTest\SerdeConfig;

use Crell\Serde\Field;

class Mail
{
    public function __construct(
        public readonly string $transport,
        #[Field(serializedName: 'transport_sendmail_command')]
        public readonly string $command,
        #[Field(serializedName: 'transport_smtp_encrypt')]
        public readonly bool $encrypt,
        #[Field(serializedName: 'transport_smtp_server')]
        public readonly string $server,
        #[Field(serializedName: 'transport_smtp_username')]
        public readonly string $username,
        #[Field(serializedName: 'transport_smtp_password')]
        public readonly string $password,
    ) {}
}
