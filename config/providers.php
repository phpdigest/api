<?php

declare(strict_types=1);

/* @var array $params */

use Yiisoft\Composer\Config\Merger\Modifier\ReverseBlockMerge;

return [
    'yiisoft/cycle-orm/repository-provider' => \Yiisoft\Yii\Cycle\Factory\RepositoryProvider::class,
    ReverseBlockMerge::class => new ReverseBlockMerge()
];
