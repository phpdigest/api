<?php

declare(strict_types=1);

namespace App\Api\Telegram\Helper;

use App\Module\Link\Api\UserLinkService;
use App\Module\User\Api\IdentityFactory;
use App\Module\User\Api\IdentityTokenService;
use App\Module\User\Domain\Entity\Token;
use Yiisoft\Auth\IdentityInterface;
use Yiisoft\Auth\IdentityRepositoryInterface;

final class TgIdentityService
{
    private UserLinkService $userLinkService;
    private IdentityRepositoryInterface $identityRepository;
    private IdentityTokenService $identityTokenService;
    private IdentityFactory $identityFactory;

    public function __construct(
        UserLinkService $userLinkService,
        IdentityRepositoryInterface $identityRepository,
        IdentityTokenService $identityTokenService,
        IdentityFactory $identityFactory
    ) {
        $this->userLinkService = $userLinkService;
        $this->identityRepository = $identityRepository;
        $this->identityTokenService = $identityTokenService;
        $this->identityFactory = $identityFactory;
    }

    public function getIdentity(string $tgToken): IdentityInterface
    {
        $identity = $this->identityRepository->findIdentityByToken($tgToken, Token::TYPE_TELEGRAM);

        if ($identity !== null) {
            return $identity;
        }

        // create identity and link with token
        $identity = $this->identityFactory->createIdentity();
        $this->identityTokenService->addIdentityToken($identity, $tgToken, Token::TYPE_TELEGRAM);
        return $identity;
    }
}
