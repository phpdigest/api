<?php

declare(strict_types=1);

namespace App\Api\External\Controller;

use App\Module\User\Api\IdentityFactory;
use Psr\Http\Message\ServerRequestInterface;

class UserController extends ApiController
{
    public function get(ServerRequestInterface $request): array
    {
        $identity = $this->getIdentityFromRequest($request);
        return ['id' => $identity->getId()];
    }

    public function post(IdentityFactory $service): array
    {
        $identity = $service->createIdentity();

        return ['token' => $identity->token];
    }
}
