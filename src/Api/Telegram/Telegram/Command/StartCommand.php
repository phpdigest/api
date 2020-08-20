<?php

declare(strict_types=1);

namespace App\Api\Telegram\Telegram\Command;

use BotMan\BotMan\BotMan;

final class StartCommand implements CommandInterface
{
    public static function getName(): string
    {
        return 'start';
    }

    public function handle(BotMan $botMan): void
    {
        $text = <<<TEXT
*Привет*.
Это бот для помощи сбора новостей из мира PHP для проекта [PHP Digest](https://phpdigest.ru).
TEXT;

        $botMan->reply($text, [
            'parse_mode' => 'Markdown',
        ]);
    }
}
