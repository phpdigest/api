<?php

declare(strict_types=1);

namespace App\Module\Rbac\Middleware;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Yiisoft\Access\AccessCheckerInterface;
use Yiisoft\Http\Header;
use Yiisoft\Http\Status;
use Yiisoft\User\User;

final class PermissionMiddleware implements MiddlewareInterface
{
    private ResponseFactoryInterface $responseFactory;
    private User $userBox;
    private AccessCheckerInterface $accessChecker;

    public function __construct(
        ResponseFactoryInterface $responseFactory,
        User $userBox,
        AccessCheckerInterface $accessChecker
    ) {
        $this->responseFactory = $responseFactory;
        $this->userBox = $userBox;
        $this->accessChecker = $accessChecker;
    }

    private ?string $permission = null;
    private ?string $redirectionUrl = null;

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($this->permission === null) {
            throw new \RuntimeException('Permission is not configured.');
        }
        $identityId = $this->userBox->getIdentity()->getId();

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
