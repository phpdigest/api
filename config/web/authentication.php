<?php

declare(strict_types=1);

use App\Common\Application\AuthRequestErrorHandler;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Yiisoft\Auth\AuthenticationMethodInterface;
use Yiisoft\Auth\Middleware\Authentication;

return [
    Authentication::class => static function (ContainerInterface $container) {
        return new Authentication(
            $container->get(AuthenticationMethodInterface::class),
            $container->get(ResponseFactoryInterface::class),
            $container->get(AuthRequestErrorHandler::class)
        );
    }
];
