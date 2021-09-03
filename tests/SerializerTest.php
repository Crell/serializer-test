<?php

declare(strict_types=1);

namespace Crell\SerializerTest;


use Crell\SerializerTest\Drupal\EmailItem;
use Crell\SerializerTest\Drupal\Field;
use Crell\SerializerTest\Drupal\FieldItemList;
use Crell\SerializerTest\Drupal\LinkItem;
use Crell\SerializerTest\Drupal\Node;
use Crell\SerializerTest\Drupal\StringItem;
use Crell\SerializerTest\Drupal\TextItem;
use Doctrine\Common\Annotations\AnnotationReader;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Mapping\ClassDiscriminatorFromClassMetadata;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\NameConverter\MetadataAwareNameConverter;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class SerializerTest extends TestCase
{
    protected function getSerializer(): Serializer
    {
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $metadataAwareNameConverter = new MetadataAwareNameConverter($classMetadataFactory);
//        $discriminator = new ClassDiscriminatorFromClassMetadata($classMetadataFactory);

        $discriminator = new ManualDiscriminator();
        $discriminator->addMap(Field::class, 'type', [
            'string' => StringItem::class,
            'email' => EmailItem::class,
            'LinkItem' => LinkItem::class,
            'text' => TextItem::class,
        ]);

        $extractor = new PropertyInfoExtractor([], [new PhpDocExtractor(), new ReflectionExtractor()]);

        $objectNormalizer =  new ObjectNormalizer(
            classMetadataFactory: $classMetadataFactory,
            nameConverter: $metadataAwareNameConverter,
            propertyTypeExtractor: $extractor,
            classDiscriminatorResolver: $discriminator,
        );

        $normalizers = [new ArrayDenormalizer(), new DateTimeNormalizer(), $objectNormalizer];

        $encoders = [new XmlEncoder(), new JsonEncoder()];

        return new Serializer($normalizers, $encoders);
    }

    /**
     * @test
     * @dataProvider roundTripProvider
     */
    public function round_trip(object $subject, string $format, ?array $fields = null, callable $tests = null): void
    {
        $serializer = $this->getSerializer();
        $serialized = $serializer->serialize($subject, $format);

        $deserialized = $serializer->deserialize(data: $serialized, type: $subject::class, format: $format);

        self::assertEquals($subject, $deserialized);

        /*
        $fields ??= $this->getFields($subject::class);

        foreach ($fields as $field) {
            self::assertEquals($subject->$field, $deserialized->$field);
        }
        */

        if ($tests) {
            $tests($subject, $deserialized);
        }
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
// There is some issue with dates and seconds/microseconds I've not figured out yet.
//                dateTimeImmutable: new \DateTimeImmutable('2021-08-06 15:48:25'),
//                dateTime: new \DateTime('2021-08-06 15:48:25'),
                simpleArray: [1, 2, 3],
                assocArray: ['a' => 'A', 'b' => 'B'],
                simpleObject: new Point(1, 2, 3),
                untyped: 5,
//                resource: \fopen(__FILE__, 'rb'),
            ),
            'format' => 'json',
        ];

        $node = new Node('A node', 3, false, false);
        $node->fields[] = new FieldItemList('en', [
            new StringItem('foo'),
            new StringItem('bar'),
        ]);
        $node->fields[] = new FieldItemList('en', [
            new EmailItem('me@example.com'),
            new EmailItem('you@example.com'),
        ]);
        $node->fields[] = new FieldItemList('en', [
            new TextItem('Stuff here', 'plain'),
            new TextItem('More things', 'raw_html'),
        ]);
        $node->fields[] = new FieldItemList('en', [
            new LinkItem(uri: 'https://typo3.com', title: 'TYPO3'),
            new LinkItem(uri: 'https://google.com', title: 'Big Evil'),
        ]);

        yield "Drupal Node" => [
            'subject' => $node,
            'format' => 'json',
            'fields' => null,
            'tests' => function (Node $original, Node $deserialized) {
                self::assertInstanceOf(FieldItemList::class, $deserialized->fields[0]);
            },
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
