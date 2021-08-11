<?php

declare(strict_types=1);

namespace Crell\SerializerTest;


use Symfony\Component\Serializer\Annotation\SerializedName;

class CustomNames
{
    public function __construct(
        #[SerializedName('firstName')]
        public string $first = '',
        #[SerializedName('lastName')]
        public string $last = '',
    ) {}
}
