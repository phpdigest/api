<?php

declare(strict_types=1);

use App\Service\Mailer;
use Psr\Container\ContainerInterface;
use Yiisoft\Aliases\Aliases;
use Yiisoft\Auth\IdentityRepositoryInterface;
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

    \App\Module\Contact\Api\ContactMailer::class => static function (ContainerInterface $container) use ($params) {
        return (new \App\Module\Contact\Service\MailerService(
            $container->get(Mailer::class),
            $params['mailer']['adminEmail']
        ));
    },

    IdentityRepositoryInterface::class => static function (ContainerInterface $container) {
        return $container->get(\Cycle\ORM\ORMInterface::class)
                         ->getRepository(\App\Common\Domain\Entity\Identity::class);
    },
];
