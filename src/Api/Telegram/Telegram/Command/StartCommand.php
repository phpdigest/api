<?php

declare(strict_types=1);

namespace App\Api\Telegram\Telegram\Command;

use BotMan\BotMan\BotMan;
use BotMan\Drivers\Telegram\Extensions\Keyboard;
use BotMan\Drivers\Telegram\Extensions\KeyboardButton;

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
            Это бот для помощи сбора новостей из мира PHP для проекта [PHP Digest](https://t.me/phpdigest).
            TEXT;

        $botMan->sendRequest(
            'sendMessage',
            [
                'text' => $text,
                'parse_mode' => 'Markdown',
            ] + self::generateMainMenuButtons()->toArray()
        );
    }

    public static function generateMainMenuButtons(): Keyboard
    {
        return Keyboard::create()
            ->type(Keyboard::TYPE_INLINE)
            ->oneTimeKeyboard(false)
            ->addRow(
                KeyboardButton::create('Предложить ссылку')->callbackData(SuggestLinkCommand::COMMAND)
            );
    }
}
