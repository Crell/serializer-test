<?php

declare(strict_types=1);

namespace Crell\SerializerTest;

use Crell\SerialzerTest\Config\BackEnd;
use Crell\SerialzerTest\Config\Caching;
use Crell\SerialzerTest\Config\Extensions;
use Crell\SerialzerTest\Config\FrontEnd;
use Crell\SerialzerTest\Config\Mail;
use Crell\SerialzerTest\Config\PasswordHashing;
use Crell\SerialzerTest\Config\System;
use Doctrine\Common\Annotations\AnnotationReader;
use PHPUnit\Framework\TestCase;
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

class SerializerConfigTest extends TestCase
{
    protected function getSerializer(): Serializer
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

    public function getConfigArray(string $key): array
    {
        $config = require __DIR__ . '/LocalConfiguration.php';

        return $config[$key] ?? [];
    }

    /**
     * @test
     * @dataProvider configProvider
     */
    public function configuration(string $key, string $class, callable $tests): void
    {
        $serializer = $this->getSerializer();

        $data = $this->getConfigArray($key);

        $out = $serializer->denormalize(data: $data, type: $class);

        self::assertInstanceOf($class, $out);

        $tests($out);
    }

    public function configProvider(): iterable
    {
        yield Mail::class => [
            'key' => 'MAIL',
            'class' => Mail::class,
            'tests' => function(Mail $out) {
                self::assertEquals('sendmail', $out->transport);
            },
        ];
        yield Extensions::class => [
            'key' => 'EXTENSIONS',
            'class' => Extensions::class,
            'tests' => function(Extensions $out) {
                self::assertEquals([
                    'backendFavicon' => '',
                    'backendLogo' => '',
                    'loginBackgroundImage' => '',
                    'loginFootnote' => '',
                    'loginHighlightColor' => '',
                    'loginLogo' => '',
                    'loginLogoAlt' => '',
                ], $out->backend);
            },
        ];
        yield FrontEnd::class => [
            'key' => 'FE',
            'class' => FrontEnd::class,
            'tests' => function (FrontEnd $out) {
                self::assertEquals(false, $out->debug);
                self::assertEquals(true, $out->disableNoCacheParameter);
                self::assertInstanceOf(PasswordHashing::class, $out->passwords);
                self::assertEquals('TYPO3\\CMS\\Core\\Crypto\\PasswordHashing\\Argon2iPasswordHash', $out->passwords->className);
            },
        ];
        yield BackEnd::class => [
            'key' => 'BE',
            'class' => BackEnd::class,
            'tests' => function (BackEnd $out) {
                self::assertEquals(false, $out->debug);
                self::assertEquals('explicitAllow', $out->explicitADmode);
                self::assertEquals('abc123', $out->installToolPassword);
                self::assertInstanceOf(PasswordHashing::class, $out->passwords);
                self::assertEquals('TYPO3\\CMS\\Core\\Crypto\\PasswordHashing\\Argon2iPasswordHash', $out->passwords->className);
            },
        ];
        yield System::class => [
            'key' => 'SYS',
            'class' => System::class,
            'tests' => function (System $out) {
                self::assertEquals(0, $out->displayErrors);
                self::assertEquals(4096, $out->exceptionalErrors);
                self::assertTrue($out->isFeatureEnabled('subrequestPageErrors'));
                self::assertFalse($out->isFeatureEnabled('not-defined'));
                self::assertFalse($out->isFeatureEnabled('unifiedPageTranslationHandling'));
                self::assertEquals([1], $out->systemMaintainers);
                self::assertInstanceOf(Caching::class, $out->caching);
                self::assertCount(5, $out->caching->cacheConfigurations);
            },
        ];
    }

    /**
     * @test
     */
    public function symfonydocs(): void
    {
        $normalizer = new ObjectNormalizer(null, null, null, new ReflectionExtractor());
        $serializer = new Serializer([new DateTimeNormalizer(), $normalizer]);

        $obj = $serializer->denormalize(
            ['inner' => ['foo' => 'foo', 'bar' => 'bar'], 'date' => '1988/01/21'],
            ObjectOuter::class
        );

        self::assertEquals('foo', $obj->inner->foo);
        self::assertEquals('bar', $obj->inner->bar);
        self::assertEquals('1988-01-21', $obj->date->format('Y-m-d'));
    }
}

class ObjectOuter
{
    public ObjectInner $inner;
    public \DateTimeInterface $date;

}

class ObjectInner
{
    public $foo;
    public $bar;
}
