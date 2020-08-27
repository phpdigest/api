<?php

declare(strict_types=1);

use App\Api\Telegram\Adapter\BotManCacheAdapter;
use App\Api\Telegram\ApiClient\BackendApiClient;
use App\Common\Domain\Entity\Identity;
use App\Common\Service\Mailer;
use App\Module\Contact\Api\ContactMailer;
use App\Module\Contact\Service\MailerService;
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
use Yiisoft\Aliases\Aliases;
use Yiisoft\Auth\AuthenticationMethodInterface;
use Yiisoft\Auth\IdentityRepositoryInterface;
use Yiisoft\Auth\Method\HttpHeader;
use Yiisoft\Factory\Definitions\Reference;

/* @var array $params */

return [
    ContainerInterface::class => static function (ContainerInterface $container) {
        return $container;
    },
    Aliases::class => [
        '__class' => Aliases::class,
        '__construct()' => [$params['aliases']],
    ],
    ContactMailer::class => static function (ContainerInterface $container) use ($params) {
        return (new MailerService(
            $container->get(Mailer::class),
            $params['mailer']['adminEmail']
        ));
    },
    IdentityRepositoryInterface::class => static function (ContainerInterface $container) {
        return $container->get(ORMInterface::class)
            ->getRepository(Identity::class);
    },
    LinkRepository::class => static function (ContainerInterface $container) {
        return $container->get(ORMInterface::class)
            ->getRepository(Link::class);
    },
    AuthenticationMethodInterface::class => static function (ContainerInterface $container) {
        $httHeader = $container->get(HttpHeader::class);
        $httHeader->setHeaderName('Authorization');

        return $httHeader;
    },
    UserLinkService::class => Reference::to(UserLink::class),
    BackendApiClient::class => [
        'class' => BackendApiClient::class,
        '__construct()' => [
            'uri' => $params['app']['api_url'],
        ],
    ],
    BotMan::class => static function ($container) use ($params) {
        $config = [
            'telegram' => [
                'token' => $params['telegram-bot']['token'],
            ],
        ];

        DriverManager::loadDriver(TelegramDriver::class);

        return BotManFactory::create($config, $container->get(BotManCacheAdapter::class));
    },
];
