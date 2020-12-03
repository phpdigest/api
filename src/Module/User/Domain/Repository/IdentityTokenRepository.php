<?php

declare(strict_types=1);

namespace App\Module\User\Domain\Repository;

use App\Common\Domain\BaseRepository;
use App\Module\User\Domain\Entity\Identity;
use App\Module\User\Domain\Entity\Token;

/**
 * @psalm-internal App\Module\User
 */
class IdentityTokenRepository extends BaseRepository
{
    private function findTokenBy(string $field, string $value): ?Token
    {
        /** @var null|Token $token */
        $token = $this->findOne([$field => $value]);
        return $token;
    }

    public function findIdentityByToken(string $token, string $type = null): ?Identity
    {
        $token = $this->findTokenBy('token', $token);
        return $token === null ? null : $token->identity;
    }
}
