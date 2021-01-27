<?php

declare(strict_types=1);

namespace App\Module\User\Api;

use Yiisoft\Auth\IdentityInterface;

interface AuthClassic
{
    public function logIn(string $username, string $password): IdentityInterface;
}
