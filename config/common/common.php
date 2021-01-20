<?php

declare(strict_types=1);

use App\Api\Telegram\Adapter\BotManCacheAdapter;
use App\Api\Telegram\Telegram\ChatConfig;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
use BotMan\Drivers\Telegram\TelegramDriver;
use Psr\Container\ContainerInterface;

/* @var array $params */

return [
    BotMan::class => static function (ContainerInterface $container) use ($params) {
        $config = [
            'telegram' => [
                'token' => $params['telegram-bot']['token'],
            ],
        ];

        DriverManager::loadDriver(TelegramDriver::class);

        return BotManFactory::create($config, $container->get(BotManCacheAdapter::class));
    },
    ChatConfig::class => [
        'cleanMode' => $params['telegram-bot']['chat-config']['clean-mode'] ?? true
    ],
];
