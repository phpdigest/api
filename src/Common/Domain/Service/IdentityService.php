<?php

namespace App\Common\Domain\Service;

use App\Common\Domain\Entity\Identity;
use Cycle\ORM\ORMInterface;
use Cycle\ORM\Transaction;

class IdentityService
{
    private ORMInterface $orm;
    public function __construct(ORMInterface $orm) {
        $this->orm = $orm;
    }

    public function createIdentity(iterable $data): Identity
    {
        $identity = new Identity();
        $this->save($identity);
        return $identity;
    }

    private function save(object ...$entities): void
    {
        $transaction = new Transaction($this->orm);
        foreach ($entities as $entity) {
            $transaction->persist($entity);
        }
        $transaction->run();
    }
}
