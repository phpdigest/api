<?php

declare(strict_types=1);

namespace App\Provider;

use App\Data\ApiBucket;
use App\Data\Format;
use Psr\Container\ContainerInterface;
use roxblnfk\SmartStream\Converter\JSONConverter;
use roxblnfk\SmartStream\ConverterMatcherInterface;
use roxblnfk\SmartStream\Matching\SimpleConverterMatcher;
use roxblnfk\SmartStream\Matching\SimpleMatcherConfig;
use Yiisoft\Di\Container;
use Yiisoft\Di\Support\ServiceProvider;

final class SmartStreamProvider extends ServiceProvider
{
    /**
     * @suppress PhanAccessMethodProtected
     */
    public function register(Container $container): void
    {
        $container->set(
            SimpleMatcherConfig::class,
            static fn(ContainerInterface $container) => (new SimpleMatcherConfig())
                ->withFormat(Format::JSON, JSONConverter::class, 'application/json', [ApiBucket::class])
        );

        $container->set(ConverterMatcherInterface::class, SimpleConverterMatcher::class);
    }
}
