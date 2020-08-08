<?php

declare(strict_types=1);

namespace App\Api\External\Controller;

use App\Api\External\Data\ApiBucket;
use App\Common\Domain\Service\IdentityService;
use Psr\Http\Message\ServerRequestInterface;
use roxblnfk\SmartStream\Data\DataBucket;
use Yiisoft\Auth\IdentityRepositoryInterface;

class UserController extends ApiController
{
    public function get(ServerRequestInterface $request, IdentityRepositoryInterface $repository): DataBucket
    {
        $token = $request->getQueryParams()['token'] ?? null;
        $this->validateToken($token);
        $identity = $repository->findIdentityByToken($token, '');
        if ($identity === null) {
            throw new \RuntimeException('User not found.');
        }
        return new ApiBucket(['id' => $identity->getId()]);
    }

    public function post(ServerRequestInterface $request, IdentityService $service): DataBucket
    {
        $data = $request->getParsedBody();
        $identity = $service->createIdentity($data);
        return new ApiBucket(['token' => $identity->getToken()]);
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
