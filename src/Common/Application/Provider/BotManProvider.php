<?php

declare(strict_types=1);

namespace App\Common\Application\Provider;

use App\Api\Telegram\Telegram\Command\CommandInterface;
use App\Api\Telegram\Telegram\Command\FallbackCommand;
use BotMan\BotMan\BotMan;
use Yiisoft\Composer\Config\Builder;
use Yiisoft\Di\Container;
use Yiisoft\Di\Support\ServiceProvider;

final class BotManProvider extends ServiceProvider
{
    public function register(Container $container): void
    {
        $botMan = $container->get(BotMan::class);
        $commands = require Builder::path('telegram-commands');

        /* @var $commands CommandInterface[] */
        foreach ($commands as $command) {
            $botMan->hears('/' . $command::getName(), static fn($botMan) => $container->get($command)->handle($botMan));
        }
        $botMan->fallback(static fn($botMan) => $container->get(FallbackCommand::class)->handle($botMan));
    }
}
