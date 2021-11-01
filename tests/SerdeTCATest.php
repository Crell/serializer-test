<?php

declare(strict_types=1);

namespace Crell\SerializerTest;

use Crell\AttributeUtils\Analyzer;
use Crell\AttributeUtils\MemoryCacheAnalyzer;
use Crell\Serde\Formatter\ArrayFormatter;
use Crell\Serde\PropertyHandler\NativeSerializePropertyReader;
use Crell\Serde\Serde;
use Crell\Serde\SerdeCommon;
use Crell\SerializerTest\SerdeConfig\BackEnd;
use Crell\SerializerTest\SerdeConfig\System\CacheConfig;
use Crell\SerializerTest\SerdeConfig\System\CacheOptions;
use Crell\SerializerTest\SerdeConfig\System\Caching;
use Crell\SerializerTest\SerdeConfig\Extensions;
use Crell\SerializerTest\SerdeConfig\FrontEnd;
use Crell\SerializerTest\SerdeConfig\Mail;
use Crell\SerializerTest\SerdeConfig\MailFormat;
use Crell\SerializerTest\SerdeConfig\PasswordHashing;
use Crell\SerializerTest\SerdeConfig\ScOptions;
use Crell\SerializerTest\SerdeConfig\System;
use Crell\SerializerTest\SerdeTCA\Ctrl;
use Crell\SerializerTest\SerdeTCA\OnChange;
use Crell\SerializerTest\SerdeTCA\Table;
use PHPUnit\Framework\TestCase;
use TYPO3\CMS\Core\Cache\Backend\SimpleFileBackend;
use TYPO3\CMS\Core\Cache\Frontend\PhpFrontend;

class SerdeTCATest extends TestCase
{
    protected array $tca;

    public function getSerde(): Serde
    {
        $analyzer = new MemoryCacheAnalyzer(new Analyzer());
        return new SerdeCommon(
            analyzer: $analyzer,
            handlers: [new NativeSerializePropertyReader()],
            formatters: [new ArrayFormatter($analyzer)],
        );
    }

    public function loadTcaArray(): array
    {
        $tca = [];
        $files = array_diff(scandir(__DIR__ . '/TCA'), ['..', '.']);
        foreach ($files as $filename) {
            $tca[basename($filename, '.php')] = require __DIR__ . '/TCA/' . $filename;
        }
        return $tca;

    }

    public function getTcaArray(string $table): array
    {
        $this->tca ??= $this->loadTcaArray();
        return $this->tca[$table] ?? [];
    }

    /**
     * @test
     * @dataProvider tableProvider
     */
    public function table_test(string $table, string $class, callable $tests): void
    {
        $serde = $this->getSerde();

        $data = $this->getTcaArray($table);

        $out = $serde->deserialize($data, from: 'array', to: $class);

        self::assertInstanceOf($class, $out);

        $tests($out);
    }

    public function tableProvider(): iterable
    {
        yield 'sys_file' => [
            'table' => 'sys_file',
            'class' => Table::class,
            'tests' => function (Table $out) {
                self::assertInstanceOf(Ctrl::class, $out->ctrl);
                self::assertEquals('name', $out->ctrl->label);
                self::assertEquals(false, $out->ctrl->adminOnly);
                self::assertEquals('', $out->ctrl->enableColumns->disabled);
                self::assertEquals(true, $out->ctrl->security->ignoreRootLevelRestriction);
                self::assertEquals('mimetypes-media-image', $out->ctrl->typeiconClasses[2]);

                self::assertEquals(OnChange::None, $out->columns['fileinfo']->onChange);
                self::assertEquals('LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:sys_file.storage', $out->columns['storage']->label);

                self::assertEquals(30, $out->columns['fileinfo']->config->type->cols);

                // The nested items list inside the flattened type object. Because hell yeah.
                self::assertEquals('LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:sys_file.type.unknown', $out->columns['type']->config->type->items[0]->label);
                self::assertCount(6, $out->columns['type']->config->type->items);

                // Check a value in the render part of the config.
                self::assertEquals(false, $out->columns['type']->config->render->allowNonIdValues);

                // The types section.
                self::assertEquals(['fileinfo', 'storage', 'missing'], $out->types[1]->showitem);
            },
        ];
    }

}
