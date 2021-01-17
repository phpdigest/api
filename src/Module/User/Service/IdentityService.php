<?php

declare(strict_types=1);

namespace App\Module\User\Service;

use Yiisoft\Yii\Cycle\Data\Writer\EntityWriter;
use App\Module\User\Api\IdentityTokenService;
use App\Module\User\Domain\Entity\Identity;
use App\Module\User\Domain\Entity\Token;
use Yiisoft\Auth\IdentityInterface;
use Yiisoft\Security\Random;

/**
 * @psalm-internal App\Module\User
 */
final class IdentityService implements \App\Module\User\Api\IdentityFactory, IdentityTokenService
{
    private const DEFAULT_TOKEN_LENGTH = 127;

    private EntityWriter $entityWriter;
    public function __construct(EntityWriter $entityWriter) {
        $this->entityWriter = $entityWriter;
    }

    public function prepareIdentity(): Identity
    {
        return new Identity(Random::string(128));
    }

    public function createIdentity(): Identity
    {
        $identity = $this->prepareIdentity();
        $this->entityWriter->write([$identity]);
        return $identity;
    }

    public function addIdentityToken(IdentityInterface $identity, string $token, string $type): Token
    {
        $token = new Token($token, $type);
        $token->identity = $identity;
        $this->entityWriter->write([$token]);
        return $token;
    }

    public function generateIdentityToken(IdentityInterface $identity, string $type): Token
    {
        $tokenStr = Random::string(self::DEFAULT_TOKEN_LENGTH);
        return $this->addIdentityToken($identity, $tokenStr, $type);
    }
}
