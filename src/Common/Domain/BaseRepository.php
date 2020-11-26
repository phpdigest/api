<?php

declare(strict_types=1);

namespace App\Common\Domain;

use Cycle\ORM\RepositoryInterface;
use Cycle\ORM\Select;
use Yiisoft\Yii\Cycle\DataReader\SelectDataReader;

abstract class BaseRepository implements RepositoryInterface
{
    protected Select $select;

    public function __construct(Select $select)
    {
        $this->select = $select;
    }

    /**
     * Repositories are always immutable by default.
     */
    public function __clone()
    {
        $this->select = clone $this->select;
    }

    public function findByPK($id): ?object
    {
        return $this->select()->wherePK($id)->fetchOne();
    }

    public function findOne(array $scope = []): ?object
    {
        return $this->select()->fetchOne($scope);
    }

    public function findAll(array $scope = [], array $orderBy = []): iterable
    {
        return new SelectDataReader($this->select()->where($scope)->orderBy($orderBy));
    }

    protected function select(): Select
    {
        return clone $this->select;
    }
}
