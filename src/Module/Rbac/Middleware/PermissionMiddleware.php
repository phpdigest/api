<?php

declare(strict_types=1);

namespace App\Module\Rbac\Middleware;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use RuntimeException;
use Yiisoft\Access\AccessCheckerInterface;
use Yiisoft\Http\Header;
use Yiisoft\Http\Status;
use Yiisoft\User\CurrentUser;

final class PermissionMiddleware implements MiddlewareInterface
{
    private ResponseFactoryInterface $responseFactory;
    private CurrentUser $currentUser;
    private AccessCheckerInterface $accessChecker;

    public function __construct(
        ResponseFactoryInterface $responseFactory,
        CurrentUser $currentUser,
        AccessCheckerInterface $accessChecker
    ) {
        $this->responseFactory = $responseFactory;
        $this->currentUser = $currentUser;
        $this->accessChecker = $accessChecker;
    }

    private ?string $permission = null;
    private ?string $redirectionUrl = null;

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($this->permission === null) {
            throw new RuntimeException('Permission is not configured.');
        }
        $identityId = $this->currentUser->getIdentity()->getId();

        if ($identityId === null || !$this->accessChecker->userHasPermission($identityId, $this->permission)) {
            return $this->redirectionUrl === null
                ? $this->responseFactory->createResponse(Status::UNAUTHORIZED, Status::TEXTS[Status::UNAUTHORIZED])
                : $this->responseFactory->createResponse(Status::FOUND, Status::TEXTS[Status::FOUND])->withHeader(
                    Header::LOCATION,
                    $this->redirectionUrl
                );
        }

        return $handler->handle($request);
    }

    public function withPermission(string $permission): self
    {
        $new = clone $this;
        $new->permission = $permission;
        return $new;
    }

    public function withRedirection(string $url): self
    {
        $new = clone $this;
        $new->redirectionUrl = $url;
        return $new;
    }
}
