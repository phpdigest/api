<?php

declare(strict_types=1);

use Yiisoft\ErrorHandler\ErrorCatcher;
use Yiisoft\Factory\Definitions\Reference;
use Yiisoft\Injector\Injector;
use Yiisoft\Middleware\Dispatcher\MiddlewareDispatcher;
use Yiisoft\Router\Middleware\Router;
use Yiisoft\Yii\Web\NotFoundHandler;

return [
    Yiisoft\Yii\Web\Application::class => [
        '__construct()' => [
            'dispatcher' => static function (Injector $injector) {
                return ($injector->make(MiddlewareDispatcher::class))
                    ->withMiddlewares(
                        [
                            Router::class,
                            ErrorCatcher::class,
                        ]
                    );
            },
            'fallbackHandler' => Reference::to(NotFoundHandler::class),
        ],
    ],
];
