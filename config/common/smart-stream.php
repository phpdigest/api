<?php

declare(strict_types=1);

use App\Api\External\Data\ApiBucket;
use App\Api\Format;
use Psr\Container\ContainerInterface;
use roxblnfk\SmartStream\Converter\JSONConverter;
use roxblnfk\SmartStream\ConverterMatcherInterface;
use roxblnfk\SmartStream\Matching\SimpleConverterMatcher;
use roxblnfk\SmartStream\Matching\SimpleMatcherConfig;
use Yiisoft\Factory\Definitions\Reference;

/**  @var array $params */

return [
    SimpleMatcherConfig::class => static fn(ContainerInterface $container) => (new SimpleMatcherConfig())
        ->withFormat(Format::JSON, JSONConverter::class, 'application/json', [ApiBucket::class]),
    ConverterMatcherInterface::class => Reference::to(SimpleConverterMatcher::class),
];
