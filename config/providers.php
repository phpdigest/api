<?php

declare(strict_types=1);

/* @var array $params */

use Yiisoft\Arrays\Modifier\ReverseBlockMerge;

return [
    'yiisoft/cycle-orm/repository-provider' => \Yiisoft\Yii\Cycle\Factory\RepositoryProvider::class,
    ReverseBlockMerge::class => new ReverseBlockMerge()
];
