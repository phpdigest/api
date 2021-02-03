<?php

declare(strict_types=1);

namespace App\Module\Link\Service;

use App\Common\Domain\Exception\EntityNotFound;
use App\Module\Link\Api\LinkSuggestionService;
use App\Module\Link\Domain\Entity\Suggestion;
use App\Api\Common\Form\CreateLinkForm;
use App\Module\Link\Domain\Repository\SuggestionRepository;
use Yiisoft\Auth\IdentityInterface;
use Yiisoft\Yii\Cycle\Data\Writer\EntityWriter;

final class SuggestionService implements LinkSuggestionService
{
    private SuggestionRepository $repository;
    private EntityWriter $entityWriter;
    private UrlService $urlService;

    public function __construct(
        SuggestionRepository $suggestionRepository,
        UrlService $urlService,
        EntityWriter $entityWriter
    ) {
        $this->repository = $suggestionRepository;
        $this->entityWriter = $entityWriter;
        $this->urlService = $urlService;
    }

    public function createSuggestion(CreateLinkForm $form, IdentityInterface $identity): Suggestion
    {
        $link = new Suggestion();

        # Find existing url or prepare new
        $url = $this->urlService->findUrl($form->getUrl()) ?? $this->urlService->prepareUrl($form->getUrl());

        $link->url = $url;
        $link->source = $form->getSource();
        $link->description = $form->getDescription();

        $link->identity = $identity;
        $this->entityWriter->write([$link]);

        return $link;
    }

    public function deleteSuggestion(string $url, IdentityInterface $identity): void
    {
        $this->repository->delete(
            $this->findSuggestion($url, $identity)
        );
    }

    public function findSuggestion(string $url, IdentityInterface $identity): Suggestion
    {
        $urlEntity = $this->urlService->findUrl($url);
        if ($urlEntity === null) {
            throw new EntityNotFound('Url not found.');
        }

        $link = $this->repository->findOneByUrlAndIdentity($urlEntity->id, $identity);
        if ($link === null) {
            throw new EntityNotFound('Suggestion not found.');
        }

        return $link;
    }
}
