<?php

declare(strict_types=1);

namespace App\Module\User\Domain\Repository;

use App\Common\Domain\BaseRepository;
use App\Module\User\Domain\Entity\Account;
use Cycle\ORM\Select;
use Yiisoft\Yii\Cycle\Data\Reader\EntityReader;

/**
 * @psalm-internal App\Module\User
 */
class AccountRepository extends BaseRepository
{
    public function findAll(array $scope = [], array $orderBy = []): EntityReader
    {
        return new EntityReader($this->select()->where($scope)->orderBy($orderBy));
    }

    public function findByUsername(string $username): ?Account
    {
        /** @var null|Account $result */
        $result = $this->select()
            ->where('username', $username)
            ->load('identity', ['method' => Select\JoinableLoader::JOIN])
            ->fetchOne();
        return $result;
    }
}
