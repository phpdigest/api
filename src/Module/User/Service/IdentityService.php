<?php

declare(strict_types=1);

namespace App\Module\User\Service;

use App\Common\Domain\Writer\EntityWriter;
use App\Module\User\Domain\Entity\Identity;
use Yiisoft\Security\Random;

/**
 * @psalm-internal App\Module\User
 */
final class IdentityService implements \App\Module\User\Api\IdentityFactory
{
    private EntityWriter $entityWriter;
    public function __construct(EntityWriter $entityWriter) {
        $this->entityWriter = $entityWriter;
    }

    public function prepareIdentity(): Identity
    {
        return new Identity(Random::string(128));
    }

    public function createIdentity(): Identity
    {
        $identity = $this->prepareIdentity();
        $this->entityWriter->write([$identity]);
        return $identity;
    }
}
