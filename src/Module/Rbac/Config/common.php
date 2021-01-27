<?php

declare(strict_types=1);

use Yiisoft\Access\AccessCheckerInterface;
use Yiisoft\Rbac\Manager;
use Yiisoft\Rbac\Php\Storage;
use Yiisoft\Rbac\RuleFactory\ClassNameRuleFactory;
use Yiisoft\Rbac\RuleFactoryInterface;
use Yiisoft\Rbac\StorageInterface;

/** @var array $params */

return [
    StorageInterface::class => [
        '__class' => Storage::class,
        '__construct()' => [
            'directory' => dirname(__DIR__) . '/Storage',
        ],
    ],
    RuleFactoryInterface::class => ClassNameRuleFactory::class,
    AccessCheckerInterface::class => Manager::class,
];
