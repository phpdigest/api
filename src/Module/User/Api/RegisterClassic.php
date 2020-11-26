<?php

declare(strict_types=1);

namespace App\Module\User\Api;

use Yiisoft\Auth\IdentityInterface;

interface RegisterClassic
{
    public function register(string $login, string $password): IdentityInterface;
}
