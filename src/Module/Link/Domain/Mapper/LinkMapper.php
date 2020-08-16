<?php

declare(strict_types=1);

namespace App\Module\Link\Domain\Mapper;

use Cycle\ORM\Command\ContextCarrierInterface;
use Cycle\ORM\Command\Database\Update;
use Cycle\ORM\Heap\Node;
use Cycle\ORM\Heap\State;
use Cycle\ORM\Mapper\Mapper;

class LinkMapper extends Mapper
{
    public function queueCreate($entity, Node $node, State $state): ContextCarrierInterface
    {
        $command = parent::queueCreate($entity, $node, $state);

        $state->register('created_at', new \DateTimeImmutable(), true);
        $command->register('created_at', new \DateTimeImmutable(), true);

        $state->register('updated_at', new \DateTimeImmutable(), true);
        $command->register('updated_at', new \DateTimeImmutable(), true);

        return $command;
    }

    /**
     * @suppress PhanUndeclaredMethod
     */
    public function queueUpdate($entity, Node $node, State $state): ContextCarrierInterface
    {
        /** @var Update $command */
        $command = parent::queueUpdate($entity, $node, $state);

        $state->register('updated_at', new \DateTimeImmutable(), true);
        $command->registerAppendix('updated_at', new \DateTimeImmutable());

        return $command;
    }
}
