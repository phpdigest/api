<?php

declare(strict_types=1);

use App\Module\User\Api\AuthClassic;
use App\Module\User\Api\IdentityFactory;
use App\Module\User\Api\IdentityTokenService;
use App\Module\User\Api\RegisterClassic;
use App\Module\User\Domain\Repository\IdentityRepository;
use App\Module\User\Domain\Repository\IdentityWithTokenRepository;
use App\Module\User\Service\AccountService;
use App\Module\User\Service\IdentityService;
use Yiisoft\Auth\IdentityRepositoryInterface;
use Yiisoft\Auth\IdentityWithTokenRepositoryInterface;
use Yiisoft\Factory\Definitions\Reference;

/* @var array $params */

return [
    IdentityRepositoryInterface::class => Reference::to(IdentityRepository::class),
    IdentityWithTokenRepositoryInterface::class => Reference::to(IdentityWithTokenRepository::class),

    AuthClassic::class => Reference::to(AccountService::class),
    RegisterClassic::class => Reference::to(AccountService::class),
    IdentityFactory::class => Reference::to(IdentityService::class),
    IdentityTokenService::class => Reference::to(IdentityService::class),
];
