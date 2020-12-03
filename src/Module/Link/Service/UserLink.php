<?php

declare(strict_types=1);

namespace App\Module\Link\Service;

use App\Common\Domain\Exception\EntityNotFound;
use App\Module\Link\Api\UserLinkService;
use App\Module\Link\Domain\Entity\Link;
use App\Api\Common\Form\CreateLinkForm;
use App\Module\Link\Domain\Repository\LinkRepository;
use Yiisoft\Auth\IdentityInterface;

final class UserLink implements UserLinkService
{
    private LinkRepository $repository;

    public function __construct(LinkRepository $repository)
    {
        $this->repository = $repository;
    }

    public function createLink(CreateLinkForm $form, IdentityInterface $identity): Link
    {
        $link = new Link();
        $link->setUrl($form->getUrl());

        if ($form->hasDescription()) {
            $link->setDescription($form->getDescription());
        }

        $link->setIdentity($identity);
        $this->repository->save($link);

        return $link;
    }

    public function deleteLink(string $url, IdentityInterface $identity): void
    {
        $this->repository->delete(
            $this->getLink($url, $identity)
        );
    }

    public function getLink(string $url, IdentityInterface $identity): Link
    {
        $link = $this->repository->findOneByUrlAndIdentity($url, $identity);
        if ($link === null) {
            throw new EntityNotFound('Url not found.');
        }

        return $link;
    }
}
