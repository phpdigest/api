<?php

declare(strict_types=1);

/* @var array $params */

use App\Common\Application\Provider\LoggerProvider;
use App\Common\Application\Provider\RouterProvider;
use App\Common\Application\Provider\SmartStreamProvider;
use Yiisoft\Arrays\Modifier\ReverseBlockMerge;

return [
    'yiisoft/router-fastroute/router' => RouterProvider::class,
    'LoggerProvider' => LoggerProvider::class,

    'roxblnfk/smart-stream/smartstream' =>  SmartStreamProvider::class,

    ReverseBlockMerge::class => new ReverseBlockMerge()
];
