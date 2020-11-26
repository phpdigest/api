<?php

declare(strict_types=1);

namespace App\Module\User\Api;

use Yiisoft\Auth\IdentityInterface;

interface AuthClassic
{
    public function login(string $login, string $password): IdentityInterface;
}
