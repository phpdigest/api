<?php

declare(strict_types=1);

namespace App\Common\Domain\Writer;

use Cycle\ORM\ORMInterface;
use Cycle\ORM\Transaction;
use Yiisoft\Data\Writer\DataWriterInterface;

final class EntityWriter implements DataWriterInterface
{
    private ORMInterface $orm;

    public function __construct(ORMInterface $orm) {
        $this->orm = $orm;
    }

    public function write(iterable $entities): void
    {
        $transaction = new Transaction($this->orm);
        foreach ($entities as $entity) {
            $transaction->persist($entity);
        }
        $transaction->run();
    }
}
