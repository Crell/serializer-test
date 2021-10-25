<?php

declare(strict_types=1);

namespace Crell\SerializerTest\Benchmarks;

use Crell\AttributeUtils\Analyzer;
use Crell\AttributeUtils\MemoryCacheAnalyzer;
use Crell\Serde\Formatter\ArrayFormatter;
use Crell\Serde\Serde;
use Crell\Serde\SerdeCommon;
use Crell\SerializerTest\SerdeConfig\BackEnd;
use Crell\SerializerTest\SerdeConfig\Extensions;
use Crell\SerializerTest\SerdeConfig\FrontEnd;
use Crell\SerializerTest\SerdeConfig\Mail;
use Crell\SerializerTest\SerdeConfig\System;
use Crell\SerializerTest\SerdeConfig\ScOptions;
use PhpBench\Benchmark\Metadata\Annotations\AfterMethods;
use PhpBench\Benchmark\Metadata\Annotations\BeforeMethods;
use PhpBench\Benchmark\Metadata\Annotations\Iterations;
use PhpBench\Benchmark\Metadata\Annotations\OutputTimeUnit;
use PhpBench\Benchmark\Metadata\Annotations\Revs;
use PhpBench\Benchmark\Metadata\Annotations\Warmup;


/**
 * @Revs(100)
 * @Iterations(5)
 * @Warmup(2)
 * @BeforeMethods({"setUp"})
 * @AfterMethods({"tearDown"})
 * @OutputTimeUnit("milliseconds", precision=3)
 */
class SerdeConfigBench
{
    protected Serde $serde;

    protected readonly array $config;

    protected readonly array $configData;

    public function setUp(): void
    {
        $analyzer = new MemoryCacheAnalyzer(new Analyzer());
        $this->serde = new SerdeCommon(
            analyzer: $analyzer,
            formatters: [new ArrayFormatter($analyzer)],
        );

        $this->config = require __DIR__ . '/../tests/DefaultConfiguration.php';
        $this->configData = iterator_to_array($this->configProvider());
    }

    public function benchAllConfig(): void
    {
        foreach ($this->configData as $test) {
            $data = $this->config[$test['key']];
            $object = $this->serde->deserialize($data, from: 'array', to: $test['class']);
        }
    }

    public function configProvider(): iterable
    {
        yield BackEnd::class => [
            'key' => 'BE',
            'class' => BackEnd::class,
        ];
        yield Extensions::class => [
            'key' => 'EXTENSIONS',
            'class' => Extensions::class,
        ];
        yield FrontEnd::class => [
            'key' => 'FE',
            'class' => FrontEnd::class,
        ];
        yield Mail::class => [
            'key' => 'MAIL',
            'class' => Mail::class,
        ];
        yield ScOptions::class => [
            'key' => 'SC_OPTIONS',
            'class' => ScOptions::class,
        ];
        yield System::class => [
            'key' => 'SYS',
            'class' => System::class,
        ];
    }

    public function tearDown() {}
}
