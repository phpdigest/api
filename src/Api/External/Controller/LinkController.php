<?php

declare(strict_types=1);

namespace App\Api\External\Controller;

use Psr\Http\Message\ServerRequestInterface;

class LinkController extends ApiController
{
    public function get(ServerRequestInterface $request)
    {
        $url = $request->getQueryParams()['url'] ?? null;
        $this->validateLink($url);
        return 'Link is valid.';
    }
    public function post()
    {
        return __METHOD__;
    }
    public function delete()
    {
        return __METHOD__;
    }

    protected function validateLink($link): void
    {
        if (!is_string($link)) {
            throw new \InvalidArgumentException('Link should be string.');
        }
        if (!filter_var($link, FILTER_VALIDATE_URL)) {
            throw new \InvalidArgumentException('Invalid link.');
        }
    }
}
