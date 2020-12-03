<?php

declare(strict_types=1);

namespace App\Module\User\Api;

use App\Module\User\Domain\Entity\Token;
use Yiisoft\Auth\IdentityInterface;

interface IdentityTokenService
{
    public function generateIdentityToken(IdentityInterface $identity, string $type): Token;
    public function addIdentityToken(IdentityInterface $identity, string $token, string $type): Token;
}
