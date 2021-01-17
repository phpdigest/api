<?php

declare(strict_types=1);

/* @var array $params */

use App\Common\Application\Provider\BotManProvider;
use Yiisoft\Arrays\Modifier\ReverseBlockMerge;

return [
    'app/botman' => BotManProvider::class,

    ReverseBlockMerge::class => new ReverseBlockMerge(),
];
