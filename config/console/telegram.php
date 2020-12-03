<?php

declare(strict_types=1);

/**
 * @var $params array
 */

return [
    \App\Api\Telegram\Command\SetWebhookCommand::class => [
        '__construct()' => [
            $params['telegram-bot']['token'],
        ],
    ],
];
