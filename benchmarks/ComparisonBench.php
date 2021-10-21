<?php

declare(strict_types=1);

namespace Crell\SerializerTest\Benchmarks;

use Crell\AttributeUtils\Analyzer;
use Crell\AttributeUtils\MemoryCacheAnalyzer;
use Crell\Serde\Formatter\ArrayFormatter;
use Crell\Serde\Serde;
use Crell\SerializerTest\Config\BackEnd;
use Crell\SerializerTest\Config\Extensions;
use Crell\SerializerTest\Config\FrontEnd;
use Crell\SerializerTest\Config\Mail;
use Crell\SerializerTest\Config\System;
use Doctrine\Common\Annotations\AnnotationReader;
use PhpBench\Benchmark\Metadata\Annotations\AfterMethods;
use PhpBench\Benchmark\Metadata\Annotations\BeforeMethods;
use PhpBench\Benchmark\Metadata\Annotations\Iterations;
use PhpBench\Benchmark\Metadata\Annotations\OutputTimeUnit;
use PhpBench\Benchmark\Metadata\Annotations\Revs;
use PhpBench\Benchmark\Metadata\Annotations\Warmup;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\NameConverter\MetadataAwareNameConverter;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * @Revs(100)
 * @Iterations(5)
 * @Warmup(2)
 * @BeforeMethods({"setUp"})
 * @AfterMethods({"tearDown"})
 * @OutputTimeUnit("milliseconds", precision=3)
 */
class ComparisonBench
{
    protected readonly Serde $serde;

    protected readonly Serializer $symfony;

    protected readonly array $config;

    protected readonly array $configData;

    public function setUp(): void
    {
        $analyzer = new MemoryCacheAnalyzer(new Analyzer());
        $this->serde = new Serde(analyzer: $analyzer, formatters: [new ArrayFormatter($analyzer)]);

        $this->symfony = $this->getSymfonySerializer();

        $this->config = require __DIR__ . '/LocalConfiguration.php';
        $this->configData = iterator_to_array($this->configProvider());
    }

    protected function getSymfonySerializer(): Serializer
    {
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $metadataAwareNameConverter = new MetadataAwareNameConverter($classMetadataFactory);

        $extractor = new PropertyInfoExtractor([], [new PhpDocExtractor(), new ReflectionExtractor()]);

        $normalizer =  new ObjectNormalizer(
            classMetadataFactory: $classMetadataFactory,
            nameConverter: $metadataAwareNameConverter,
            propertyTypeExtractor: $extractor,
        );

        return new Serializer([new ArrayDenormalizer(), new DateTimeNormalizer(), $normalizer]);
    }

    public function tearDown(): void {}

    public function benchSerde(): void
    {
        foreach ($this->configData as $test) {
            $data = $this->config[$test['key']];
            $object = $this->serde->deserialize($data, from: 'array', to: $test['class']);
            $array = $this->serde->serialize($object, 'array');
        }
    }

    public function benchSymfony(): void
    {
        foreach ($this->configData as $test) {
            $data = $this->config[$test['key']];
            $object = $this->symfony->denormalize($data, $test['class']);
            $array = $this->symfony->normalize($object);
        }
    }

    public function configProvider(): iterable
    {
        yield Mail::class => [
            'key' => 'MAIL',
            'class' => Mail::class,
        ];
        yield Extensions::class => [
            'key' => 'EXTENSIONS',
            'class' => Extensions::class,
        ];
        yield FrontEnd::class => [
            'key' => 'FE',
            'class' => FrontEnd::class,
        ];
        yield BackEnd::class => [
            'key' => 'BE',
            'class' => BackEnd::class,
        ];
        yield System::class => [
            'key' => 'SYS',
            'class' => System::class,
        ];
    }

/*
    public function benchAllFields(): void
    {
        $data = new AllFieldTypes(
            anint: 5,
            string: 'hello',
            afloat: 3.14,
            bool: true,
            dateTimeImmutable: new \DateTimeImmutable('2021-05-01 08:30:45', new \DateTimeZone('America/Chicago')),
            dateTime: new \DateTime('2021-05-01 08:30:45', new \DateTimeZone('America/Chicago')),
            dateTimeZone: new \DateTimeZone('America/Chicago'),
            simpleArray: ['a', 'b', 'c', 1, 2, 3],
            assocArray: ['a' => 'A', 'b' => 'B', 'c' => 'C'],
            simpleObject: new Point(4, 5, 6),
            objectList: [new Point(1, 2, 3), new Point(4, 5, 6)],
            nestedArray: [
                'a' => [1, 2, 3],
                'b' => ['a' => 1, 'b' => 2, 'c' => 3],
                'c' => 'normal',
            ],
            size: Size::Large,
            backedSize: BackedSize::Large,
        );

        $serialized = $this->serde->serialize($data, 'json');

        $result = $this->serde->deserialize($serialized, from: 'json', to: AllFieldTypes::class);
    }
*/

}
