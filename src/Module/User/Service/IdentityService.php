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
        return new Identity();
    }

    public function createIdentity(): Identity
    {
        $identity = $this->prepareIdentity();
        $this->entityWriter->write([$identity]);
        return $identity;
    }

    public function createIdentityWithToken(string $type, ?string $token): Token
    {
        $identity = $this->prepareIdentity();
        return $this->addTokenToIdentity($identity, $type, $token);
    }

    public function addTokenToIdentity(IdentityInterface $identity, string $type, ?string $token): Token
    {
        $tokenEntity = new Token($token ?? $this->generateToken(), $type);
        $tokenEntity->identity = $identity;
        $this->entityWriter->write([$tokenEntity]);
        return $tokenEntity;
    }

    protected function generateToken(): string
    {
        return Random::string(self::DEFAULT_TOKEN_LENGTH);
    }
}
