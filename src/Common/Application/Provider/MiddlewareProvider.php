<?php

declare(strict_types=1);

namespace App\Common\Application\Provider;

use App\Common\Application\AuthRequestErrorHandler;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Yiisoft\Auth\AuthenticationMethodInterface;
use Yiisoft\Auth\Middleware\Authentication;
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
        $container->set(
            MiddlewareDispatcher::class,
            static function (ContainerInterface $container) {
                return (new MiddlewareDispatcher($container))
                    ->addMiddleware($container->get(Router::class))
                    ->addMiddleware($container->get(ErrorCatcher::class));
            }
        );

        $container->set(
            Authentication::class,
            static function (ContainerInterface $container) {
                return new Authentication(
                    $container->get(AuthenticationMethodInterface::class),
                    $container->get(ResponseFactoryInterface::class),
                    $container->get(AuthRequestErrorHandler::class)
                );
            }
        );
    }
}
