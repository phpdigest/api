<?php

declare(strict_types=1);

namespace App\Module\Link\Domain\Repository;

use App\Common\Domain\BaseRepository;
use App\Module\Link\Domain\Entity\Suggestion;
use Cycle\ORM\ORMInterface;
use Cycle\ORM\Select;
use Cycle\ORM\Transaction;
use Yiisoft\Auth\IdentityInterface;
use Yiisoft\Yii\Cycle\Data\Reader\EntityReader;

/**
 * @method Suggestion findOne(array $scope = [])
 * @method Suggestion findByPK($id)
 */
final class SuggestionRepository extends BaseRepository
{
    private ORMInterface $orm;

    public function __construct(Select $select, ORMInterface $orm)
    {
        $this->orm = $orm;
        parent::__construct($select);
    }

    public function findOneByUrlAndIdentity(string $url, IdentityInterface $identity): ?Suggestion
    {
        return $this->findOne(['url' => $url, 'identity_id' => $identity->getId()]);
    }

    /**
     * todo: should be moved from this Module
     */
    public function findAllWithUserAccount(array $scope = [], array $orderBy = []): EntityReader
    {
        return new EntityReader(
            $this->select()
                ->where($scope)
                ->orderBy($orderBy)
                ->load('identity', ['method' => Select::SINGLE_QUERY])
                ->load('identity.account', ['method' => Select::SINGLE_QUERY])
        );
    }

    public function delete(Suggestion $link): void
    {
        $transaction = new Transaction($this->orm);
        $transaction->delete($link);
        $transaction->run();
    }
}
