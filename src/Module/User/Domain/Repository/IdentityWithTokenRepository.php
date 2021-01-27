<?php

declare(strict_types=1);

namespace App\Module\User\Domain\Repository;

use App\Common\Domain\BaseRepository;
use App\Module\User\Domain\Entity\Identity;
use App\Module\User\Domain\Entity\Token;
use Cycle\ORM\Select;
use Yiisoft\Auth\IdentityWithTokenRepositoryInterface;

/**
 * @psalm-internal App\Module\User
 */
class IdentityWithTokenRepository extends BaseRepository implements IdentityWithTokenRepositoryInterface
{
    public function findIdentityByToken(string $token, string $type = null): ?Identity
    {
        /** @var Token $token */
        $token = $this->select()
            ->load('identity', ['method' => Select::SINGLE_QUERY])
            ->fetchOne(['token' => $token, 'type' => $type]);
        return $token === null ? null : $token->identity;
    }
}
