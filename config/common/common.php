<?php

declare(strict_types=1);

use App\Api\Telegram\Adapter\BotManCacheAdapter;
use App\Common\Domain\Entity\Identity;
use App\Module\Link\Api\UserLinkService;
use App\Module\Link\Domain\Entity\Link;
use App\Module\Link\Domain\Repository\LinkRepository;
use App\Module\Link\Service\UserLink;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
use BotMan\Drivers\Telegram\TelegramDriver;
use Cycle\ORM\ORMInterface;
use Psr\Container\ContainerInterface;
use Yiisoft\Auth\AuthenticationMethodInterface;
use Yiisoft\Auth\IdentityRepositoryInterface;
use Yiisoft\Auth\Method\HttpHeader;
use Yiisoft\Factory\Definitions\Reference;

/* @var array $params */

return [
    # Repositories
    IdentityRepositoryInterface::class => static function (ContainerInterface $container) {
        return $container->get(ORMInterface::class)
            ->getRepository(Identity::class);
    },
    LinkRepository::class => static function (ContainerInterface $container) {
        return $container->get(ORMInterface::class)
            ->getRepository(Link::class);
    },

    AuthenticationMethodInterface::class => static function (ContainerInterface $container) {
        return $container->get(HttpHeader::class)
                         ->withHeaderName('Authorization');
    },

    UserLinkService::class => Reference::to(UserLink::class),
    BotMan::class => static function (ContainerInterface $container) use ($params) {
        $config = [
            'telegram' => [
                'token' => $params['telegram-bot']['token'],
            ],
        ];

        DriverManager::loadDriver(TelegramDriver::class);

        return BotManFactory::create($config, $container->get(BotManCacheAdapter::class));
    },
];
