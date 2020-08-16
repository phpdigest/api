<?php

declare(strict_types=1);

namespace App\Api\External\Controller;

use App\Api\External\Exception\HttpException;
use App\Common\Domain\Service\IdentityService;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\Http\Status;

class UserController extends ApiController
{
    public function get(ServerRequestInterface $request): array
    {
        $identity = $this->getIdentityFromRequest($request);
        if ($identity === null) {
            throw new HttpException(Status::NOT_FOUND, 'User not found.');
        }
        return ['id' => $identity->getId()];
    }

    public function post(ServerRequestInterface $request, IdentityService $service): array
    {
        $identity = $service->createIdentity($request->getParsedBody());

        return ['token' => $identity->getToken()];
    }
}
