<?php

declare(strict_types=1);

namespace App\Module\Link\Domain\Repository;

use App\Common\Domain\BaseRepository;
use App\Module\Link\Domain\Entity\Link;
use App\Module\User\Domain\Entity\Identity;
use Cycle\ORM\ORMInterface;
use Cycle\ORM\Select;
use Cycle\ORM\Transaction;

/**
 * @method Link findOne(array $scope = [])
 * @method Link findByPK($id)
 */
final class LinkRepository extends BaseRepository
{
    private ORMInterface $orm;

    public function __construct(Select $select, ORMInterface $orm)
    {
        $this->orm = $orm;
        parent::__construct($select);
    }

    public function findOneByUrlAndIdentity(string $url, Identity $identity): ?Link
    {
        return $this->findOne(['url' => $url, 'identity_id' => $identity->getId()]);
    }

    public function save(Link $link): void
    {
        $transaction = new Transaction($this->orm);
        $transaction->persist($link);
        $transaction->run();
    }

    public function delete(Link $link): void
    {
        $transaction = new Transaction($this->orm);
        $transaction->delete($link);
        $transaction->run();
    }
}
