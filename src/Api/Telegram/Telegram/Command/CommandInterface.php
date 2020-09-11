<?php

declare(strict_types=1);

namespace App\Api\Telegram\Telegram\Command;

use BotMan\BotMan\BotMan;

interface CommandInterface
{
    public static function getName(): string;

    public function handle(BotMan $botMan): void;
}
