<?php

declare(strict_types=1);

namespace App\Module\Link\Service;

use App\Common\Domain\Exception\EntityNotFound;
use App\Module\Link\Api\UserLinkService;
use App\Module\User\Domain\Entity\Identity;
use App\Module\Link\Domain\Entity\Link;
use App\Api\Common\Form\CreateLinkForm;
use App\Module\Link\Domain\Repository\LinkRepository;

final class UserLink implements UserLinkService
{
    private LinkRepository $repository;

    public function __construct(LinkRepository $repository)
    {
        $this->repository = $repository;
    }

    public function createLink(CreateLinkForm $form, Identity $identity): Link
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

    public function deleteLink(string $url, Identity $identity): void
    {
        $this->repository->delete(
            $this->getLink($url, $identity)
        );
    }

    public function getLink(string $url, Identity $identity): Link
    {
        $link = $this->repository->findOneByUrlAndIdentity($url, $identity);
        if ($link === null) {
            throw new EntityNotFound('Url not found.');
        }

        return $link;
    }
}
