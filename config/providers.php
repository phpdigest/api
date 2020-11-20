<?php

declare(strict_types=1);

/* @var array $params */

use App\Common\Application\Provider\LoggerProvider;
use Yiisoft\Arrays\Modifier\ReverseBlockMerge;

return [
    'LoggerProvider' => LoggerProvider::class,

    ReverseBlockMerge::class => new ReverseBlockMerge()
];
