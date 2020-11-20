<?php

declare(strict_types=1);

use Psr\Container\ContainerInterface;
use Yiisoft\Router\Middleware\Router;
use Yiisoft\ErrorHandler\ErrorCatcher;
use Yiisoft\Yii\Web\MiddlewareDispatcher;

return [
    MiddlewareDispatcher::class => static fn (ContainerInterface $container) => (new MiddlewareDispatcher($container))
        ->addMiddleware($container->get(Router::class))
        ->addMiddleware($container->get(ErrorCatcher::class)),
];
