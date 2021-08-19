<?php

declare(strict_types=1);

namespace Crell\SerializerTest\Drupal;

use Symfony\Component\Serializer\Annotation\DiscriminatorMap;

trait TimeTrackable
{
    public \DateTimeImmutable $createdTime;
    public \DateTimeImmutable $updatedTime;
}

trait Fieldable
{
    /** @var array<int, FieldItemList>  */
    public array $fields;
}

class User
{
//    use TimeTrackable;
    use Fieldable;

    public int $uid;

    public function __construct(
        public string $name,
    ) {}

}

class Node
{
//    use TimeTrackable;
    use Fieldable;

    public int $nid;

    public function __construct(
        public string $title,
        public int $uid,
        public bool $promoted = false,
        public bool $sticky = false,
    ) {}

}

class FieldItemList
{
    public function __construct(
        public string $langcode = 'en',
        /** @var array<int, Field> */
        public array $list = [],
    ) {}
}

// This is problematic, as it makes the list non-extensible.
// Unless there's some even uglier way of doing it?
#[DiscriminatorMap(typeProperty: 'type', mapping: [
    'string' => StringItem::class,
    'email' => EmailItem::class,
    'LinkItem' => LinkItem::class,
    'text' => TextItem::class,
])]
class Field
{
    public int $nid;
    public int $delta;
}

class StringItem extends Field
{
    public function __construct(public string $value) {}
}

class EmailItem extends Field
{
    public function __construct(public string $email) {}
}

// We can totally do better than this thanks to JSON.
class MapItem extends Field
{
    public string $value;
}

class LinkItem extends Field
{
    public function __construct(
        public string $uri,
        public string $title,
        public array $options = [],
    ) {}
}

class TextItem extends Field
{
    public function __construct(
        public string $value,
        public string $format,
    ) {}

    protected string $processed;

    public function processed(): string
    {
        return $this->processed ??= $this->formatValue();
    }

    protected function formatValue(): string
    {
        // Something fancier goes here, obviously.
        return $this->value;
    }
}
