<?php

declare(strict_types=1);

namespace Crell\SerializerTest;

use Crell\AttributeUtils\Analyzer;
use Crell\AttributeUtils\MemoryCacheAnalyzer;
use Crell\Serde\Formatter\ArrayFormatter;
use Crell\Serde\Serde;
use Crell\SerializerTest\SerdeConfig\BackEnd;
use Crell\SerializerTest\SerdeConfig\CacheConfig;
use Crell\SerializerTest\SerdeConfig\CacheOptions;
use Crell\SerializerTest\SerdeConfig\Caching;
use Crell\SerializerTest\SerdeConfig\Extensions;
use Crell\SerializerTest\SerdeConfig\FrontEnd;
use Crell\SerializerTest\SerdeConfig\Mail;
use Crell\SerializerTest\SerdeConfig\PasswordHashing;
use Crell\SerializerTest\SerdeConfig\System;
use PHPUnit\Framework\TestCase;
use TYPO3\CMS\Core\Cache\Backend\SimpleFileBackend;
use TYPO3\CMS\Core\Cache\Frontend\PhpFrontend;

class SerdeConfigTest extends TestCase
{

    public function getSerde(): Serde
    {
        $analyzer = new MemoryCacheAnalyzer(new Analyzer());
        return new Serde(
            analyzer: $analyzer,
            formatters: [new ArrayFormatter($analyzer)],
        );
    }

    public function getConfigArray(string $key): array
    {
        $config = require __DIR__ . '/DefaultConfiguration.php';

        return $config[$key] ?? [];
    }


    /**
     * @test
     * @dataProvider configProvider
     */
    public function configuration(string $key, string $class, callable $tests): void
    {
        $serde = $this->getSerde();

        $data = $this->getConfigArray($key);

        $out = $serde->deserialize($data, from: 'array', to: $class);

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
        /*
        // @todo This one is going to be... exciting.
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
                ], $out->extensions['backend']);
            },
        ];
        */

        yield FrontEnd::class => [
            'key' => 'FE',
            'class' => FrontEnd::class,
            'tests' => function (FrontEnd $out) {
                self::assertEquals(false, $out->debug);
                self::assertEquals(false, $out->disableNoCacheParameter);
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
                self::assertEquals('', $out->installToolPassword);
                self::assertInstanceOf(PasswordHashing::class, $out->passwords);
                self::assertEquals('TYPO3\\CMS\\Core\\Crypto\\PasswordHashing\\Argon2iPasswordHash', $out->passwords->className);
            },
        ];
        yield System::class => [
            'key' => 'SYS',
            'class' => System::class,
            'tests' => function (System $out) {
                self::assertEquals(-1, $out->displayErrors);
                self::assertEquals(4096, $out->exceptionalErrors);
                self::assertFalse($out->isFeatureEnabled('subrequestPageErrors'));
                self::assertFalse($out->isFeatureEnabled('not-defined'));
                self::assertFalse($out->isFeatureEnabled('unifiedPageTranslationHandling'));
                self::assertEquals([], $out->systemMaintainers);
                self::assertInstanceOf(Caching::class, $out->caching);
                self::assertCount(12, $out->caching->cacheConfigurations);
                self::assertInstanceOf(CacheConfig::class, $out->caching['core']);
                self::assertEquals(PhpFrontend::class, $out->caching['core']->frontend);
                self::assertEquals(SimpleFileBackend::class, $out->caching['core']->backend);
                self::assertInstanceOf(CacheOptions::class, $out->caching['core']->options);
                self::assertEquals(0, $out->caching['core']->options->defaultLifetime);
                self::assertEquals(false, $out->caching['core']->options->compression);
            },
        ];
    }

}
