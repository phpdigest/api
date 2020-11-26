<?php

declare(strict_types=1);

namespace App\Module\User\Api;

use App\Module\User\Domain\Entity\Identity;

interface IdentityFactory
{
    public function createIdentity(): Identity;
}
