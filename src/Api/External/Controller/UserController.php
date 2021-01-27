<?php

declare(strict_types=1);

namespace App\Api\External\Controller;

use App\Module\User\Api\IdentityTokenService;
use App\Module\User\Domain\Entity\Token;
use Psr\Http\Message\ServerRequestInterface;

class UserController extends ApiController
{
    public function get(ServerRequestInterface $request): array
    {
        $identity = $this->getIdentityFromRequest($request);
        return ['id' => $identity->getId()];
    }

    public function post(IdentityTokenService $service): array
    {
        $token = $service->createIdentityWithToken(Token::TYPE_API, null);

        return ['token' => $token->token];
    }
}
