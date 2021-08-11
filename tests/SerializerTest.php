<?php

declare(strict_types=1);

namespace Crell\SerializerTest;


use Doctrine\Common\Annotations\AnnotationReader;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\NameConverter\MetadataAwareNameConverter;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class SerializerTest extends TestCase
{
    protected function getSerializer(): Serializer
    {
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer(), new DateTimeNormalizer()];

        return new Serializer($normalizers, $encoders);
    }

    /**
     * @test-disable
     * @dataProvider roundTripProvider
     */
    public function round_trip(object $subject, string $format, ?array $fields = null): void
    {
        $serializer = $this->getSerializer();
        $serialized = $serializer->serialize($subject, $format);

        var_dump($serialized);

        $deserialized = $serializer->deserialize(data: $serialized, type: $subject::class, format: $format);

        self::assertEquals($subject, $deserialized);

        /*
        $fields ??= $this->getFields($subject::class);

        foreach ($fields as $field) {
            self::assertEquals($subject->$field, $deserialized->$field);
        }
        */
    }

    public function roundTripProvider(): iterable
    {
        yield Point::class => [
            'subject' => new Point(1, 2, 3),
            'format' => 'json',
        ];

        yield AllFieldTypes::class => [
            'subject' => new AllFieldTypes(
                anint: 1,
                string: 'beep',
                afloat: 5.5,
                bool: true,
                dateTimeImmutable: new \DateTimeImmutable('2021-08-06 15:48:25'),
                dateTime: new \DateTime('2021-08-06 15:48:25'),
                simpleArray: [1, 2, 3],
                assocArray: ['a' => 'A', 'b' => 'B'],
                simpleObject: new Point(1, 2, 3),
                untyped: 5,
//                resource: \fopen(__FILE__, 'rb'),
            ),
            'format' => 'json',
        ];
    }

    /**
     * @test
     */
    public function changes(): void
    {
        $subject = new CustomNames(first: 'Larry', last: 'Garfield');

        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));

        $metadataAwareNameConverter = new MetadataAwareNameConverter($classMetadataFactory);

        $serializer = new Serializer(
            [new ObjectNormalizer($classMetadataFactory, $metadataAwareNameConverter)],
            ['json' => new JsonEncoder()]
        );

//        $serializer = $this->getSerializer();
        $serialized = $serializer->serialize($subject, 'json');

        $expectedJson = json_encode(['firstName' => 'Larry', 'lastName' => 'Garfield'], JSON_THROW_ON_ERROR);

        self::assertEquals($expectedJson, $serialized);

        $deserialized = $serializer->deserialize($serialized, $subject::class, 'json');

        self::assertEquals($subject, $deserialized);
    }

}
