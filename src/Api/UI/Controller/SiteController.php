<?php

declare(strict_types=1);

namespace App\Api\UI\Controller;

use App\Module\Link\Domain\Repository\LinkRepository;
use App\Module\User\Domain\Repository\AccountRepository;
use App\Module\User\Domain\Repository\IdentityRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\User\User;

class SiteController extends AbstractController
{
    public function index(ServerRequestInterface $request): ResponseInterface
    {
        return $this->render('site/index');
    }

    public function tables(
        ServerRequestInterface $request,
        LinkRepository $linkRepository,
        IdentityRepository $identityRepository,
        AccountRepository $accountRepository,
        User $user
    ): ResponseInterface {
        return $this->render(
            'data/tables',
            [
                'links' => $linkRepository->findAll(),
                'identities' => $identityRepository->findAll(),
                'accounts' => $accountRepository->findAll(),
                'user' => $user->getIdentity(),
            ]
        );
    }
}
