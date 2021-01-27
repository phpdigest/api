<?php

declare(strict_types=1);

namespace App\Module\Link\Service;

use App\Common\Domain\Exception\EntityNotFound;
use App\Module\Link\Api\UserLinkService;
use App\Module\Link\Domain\Entity\Suggestion;
use App\Api\Common\Form\CreateLinkForm;
use App\Module\Link\Domain\Repository\SuggestionRepository;
use Yiisoft\Auth\IdentityInterface;
use Yiisoft\Yii\Cycle\Data\Writer\EntityWriter;

final class UserLink implements UserLinkService
{
    private SuggestionRepository $repository;
    private EntityWriter $entityWriter;

    public function __construct(
        SuggestionRepository $repository,
        EntityWriter $entityWriter
    ) {
        $this->repository = $repository;
        $this->entityWriter = $entityWriter;
    }

    public function createSuggestion(CreateLinkForm $form, IdentityInterface $identity): Suggestion
    {
        $link = new Suggestion();
        $link->url = $form->getUrl();
        $link->source = $form->getSource();
        $link->description = $form->getDescription();

        $link->identity = $identity;
        $this->entityWriter->write([$link]);

        return $link;
    }

    public function deleteLink(string $url, IdentityInterface $identity): void
    {
        $this->repository->delete(
            $this->getLink($url, $identity)
        );
    }

    public function getLink(string $url, IdentityInterface $identity): Suggestion
    {
        $link = $this->repository->findOneByUrlAndIdentity($url, $identity);
        if ($link === null) {
            throw new EntityNotFound('Url not found.');
        }

        return $link;
    }
}
