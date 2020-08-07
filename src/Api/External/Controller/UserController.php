<?php

declare(strict_types=1);

namespace App\Api\External\Controller;

use App\Api\External\Data\ApiBucket;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\Auth\IdentityRepositoryInterface;

class UserController extends ApiController
{
    public function get(ServerRequestInterface $request, IdentityRepositoryInterface $repository)
    {
        $token = $request->getQueryParams()['token'] ?? null;
        $this->validateToken($token);
        $identity = $repository->findIdentityByToken($token, '');
        if ($identity === null) {
            throw new \RuntimeException('User not found.');
        }
        return new ApiBucket(['id' => $identity->getId()]);
    }

    protected function validateToken($token): void
    {
        if (!is_string($token)) {
            throw new \InvalidArgumentException('Token should be string.');
        }
        if (strlen($token) !== 128) {
            throw new \InvalidArgumentException('Invalid token.');
        }
    }
}
