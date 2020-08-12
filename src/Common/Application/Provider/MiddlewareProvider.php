<?php

declare(strict_types=1);

namespace App\Common\Application\Provider;

use Psr\Container\ContainerInterface;
use Yiisoft\Di\Container;
use Yiisoft\Di\Support\ServiceProvider;
use Yiisoft\Router\Middleware\Router;
use Yiisoft\Yii\Web\MiddlewareDispatcher;
use Yiisoft\Yii\Web\ErrorHandler\ErrorCatcher;

final class MiddlewareProvider extends ServiceProvider
{
    /**
     * @suppress PhanAccessMethodProtected
     */
    public function register(Container $container): void
    {
        $container->set(MiddlewareDispatcher::class, static function (ContainerInterface $container) {
            return (new MiddlewareDispatcher($container))
                ->addMiddleware($container->get(Router::class))
                // ->addMiddleware($container->get(Yiisoft\Yii\Web\Middleware\SubFolder::class))
                // ->addMiddleware($container->get(Yiisoft\Yii\Web\Session\SessionMiddleware::class))
                // ->addMiddleware($container->get(Yiisoft\Yii\Web\Middleware\Csrf::class))
                ->addMiddleware($container->get(ErrorCatcher::class));
        });
    }
}
