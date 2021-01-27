<?php

declare(strict_types=1);

namespace App\Api\Telegram\Helper;

use App\Module\User\Api\IdentityTokenService;
use App\Module\User\Domain\Entity\Token;
use Yiisoft\Auth\IdentityInterface;
use Yiisoft\Auth\IdentityWithTokenRepositoryInterface;

final class TgIdentityService
{
    private IdentityWithTokenRepositoryInterface $identityRepository;
    private IdentityTokenService $identityTokenService;

    public function __construct(
        IdentityWithTokenRepositoryInterface $identityRepository,
        IdentityTokenService $identityTokenService
    ) {
        $this->identityRepository = $identityRepository;
        $this->identityTokenService = $identityTokenService;
    }

    public function getIdentity(string $tgToken): IdentityInterface
    {
        $identity = $this->identityRepository->findIdentityByToken($tgToken, Token::TYPE_TELEGRAM);

        if ($identity !== null) {
            return $identity;
        }

        $token = $this->identityTokenService->createIdentityWithToken(Token::TYPE_TELEGRAM, $tgToken);
        return $token->identity;
    }
}
