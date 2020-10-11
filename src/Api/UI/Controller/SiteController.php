<?php

declare(strict_types=1);

namespace App\Api\UI\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\Csrf\CsrfMiddleware;

class SiteController extends AbstractController
{
    public function index(ServerRequestInterface $request): ResponseInterface
    {
        return $this->render(
            'site/index',
            [
                'csrf' => $request->getAttribute(CsrfMiddleware::PARAMETER_NAME)
            ]
        );
    }

    public function about(ServerRequestInterface $request): ResponseInterface
    {
        return $this->render(
            'site/about',
            [
                'csrf' => $request->getAttribute(CsrfMiddleware::PARAMETER_NAME)
            ]
        );
    }

    public function getViewPath(): string
    {
        return $this->aliases->get('@views');
    }
}
