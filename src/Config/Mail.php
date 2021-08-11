<?php

declare(strict_types=1);

namespace Crell\SerializerTest\Config;

use Symfony\Component\Serializer\Annotation\SerializedName;

class Mail
{
    public  string $transport;
    #[SerializedName('transport_sendmail_command')]
    public  string $command;
    #[SerializedName('transport_smtp_encrypt')]
    public  string $encrypt;
    #[SerializedName('transport_smtp_server')]
    public  string $server;
    #[SerializedName('transport_smtp_username')]
    public  string $username;
    #[SerializedName('transport_smtp_password')]
    public  string $password;
}