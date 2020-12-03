<?php

declare(strict_types = 1);

namespace App\Module\User\Domain\Mapper;

use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Table;
use Cycle\ORM\Command\ContextCarrierInterface;
use Cycle\ORM\Heap\Node;
use Cycle\ORM\Heap\State;
use Cycle\ORM\Mapper\Mapper;

/**
 * @Table(
 *      columns={"created_at": @Column(type="datetime")}
 * )
 */
class TokenMapper extends Mapper
{
    public function queueCreate($entity, Node $node, State $state): ContextCarrierInterface
    {
        $command = parent::queueCreate($entity, $node, $state);

        $state->register('created_at', new \DateTimeImmutable(), true);
        $command->register('created_at', new \DateTimeImmutable(), true);

        return $command;
    }
}
