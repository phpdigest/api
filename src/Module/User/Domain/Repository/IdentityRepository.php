<?php

declare(strict_types=1);

namespace App\Module\User\Domain\Repository;

use App\Common\Domain\BaseRepository;
use App\Module\User\Domain\Entity\Identity;
use Yiisoft\Auth\IdentityInterface;
use Yiisoft\Auth\IdentityRepositoryInterface;

/**
 * @psalm-internal App\Module\User
 */
class IdentityRepository extends BaseRepository implements IdentityRepositoryInterface
{
    private function findIdentityBy(string $field, string $value): ?Identity
    {
        /** @var null|Identity $identity */
        $identity = $this->findOne([$field => $value]);
        return $identity;
    }

    public function findIdentity(string $id): ?Identity
    {
        /** @var null|Identity $identity */
        $identity = $this->findByPK($id);
        return $identity;
    }

    public function findIdentityByToken(string $token, string $type = null): ?IdentityInterface
    {
        return $this->findIdentityBy('token', $token);
    }
}
