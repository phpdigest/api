<?php

declare(strict_types=1);

namespace App\Module\User\Api;

use App\Module\User\Domain\Entity\Token;
use Yiisoft\Auth\IdentityInterface;

interface IdentityTokenService
{
    /**
     * @param null|string $token Will be generated if null
     *
     * Link generated token string to Identity
     */
    public function addTokenToIdentity(IdentityInterface $identity, string $type, ?string $token): Token;

    /**
     * @param null|string $token Will be generated if null
     *
     * @return Token With linked Identity {@see Token::$identity}
     */
    public function createIdentityWithToken(string $type, ?string $token): Token;
}
