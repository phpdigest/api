<?php

declare(strict_types=1);

use App\Module\User\Api\AuthClassic;
use App\Module\User\Api\IdentityFactory;
use App\Module\User\Api\RegisterClassic;
use App\Module\User\Domain\Entity\Account;
use App\Module\User\Domain\Entity\Identity;
use App\Module\User\Domain\Repository\AccountRepository;
use App\Module\User\Domain\Repository\IdentityRepository;
use App\Module\User\Service\AccountService;
use App\Module\User\Service\IdentityService;
use Cycle\ORM\ORMInterface;
use Psr\Container\ContainerInterface;
use Yiisoft\Auth\IdentityRepositoryInterface;
use Yiisoft\Factory\Definitions\Reference;

/* @var array $params */

return [
    IdentityRepositoryInterface::class => Reference::to(IdentityRepository::class),

    AuthClassic::class => Reference::to(AccountService::class),
    RegisterClassic::class => Reference::to(AccountService::class),
    IdentityFactory::class => Reference::to(IdentityService::class),

    IdentityRepository::class => static fn (ContainerInterface $container)
        => $container->get(ORMInterface::class)->getRepository(Identity::class),
    AccountRepository::class => static fn (ContainerInterface $container)
        => $container->get(ORMInterface::class)->getRepository(Account::class),
];
