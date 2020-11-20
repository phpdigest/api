<?php

declare(strict_types=1);

use App\Module\Contact\Api\ContactMailer;
use App\Module\Contact\Service\MailerService;

/**  @var array $params */

return [
    ContactMailer::class => [
        '__class' => MailerService::class,
        '__construct()' => [
            'to' => $params['mailer']['adminEmail']
        ]
    ]
];
