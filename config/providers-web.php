<?php

declare(strict_types=1);

/* @var array $params */

use App\Common\Application\Provider\BotManProvider;
use Yiisoft\Composer\Config\Merger\Modifier\ReverseBlockMerge;

return [
    'app/botman' => BotManProvider::class,

    ReverseBlockMerge::class => new ReverseBlockMerge(),
];
