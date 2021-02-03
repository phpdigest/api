<?php

declare(strict_types=1);

namespace App\Api\External\Controller;

use App\Api\External\Exception\HttpException;
use App\Module\Link\Api\LinkSuggestionService;
use App\Api\Common\Form\CreateLinkForm;
use App\Api\Common\Form\FindLinkForm;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\Form\FormModel;
use Yiisoft\Http\Status;

final class LinkController extends ApiController
{
    public function get(LinkSuggestionService $service, ServerRequestInterface $request, FindLinkForm $form): array
    {
        $this->validateLinkForm($form, $request->getQueryParams());
        $link = $service->findSuggestion($form->getUrl(), $this->getIdentityFromRequest($request));

        return ['url' => $link->url->__toString()];
    }

    public function post(LinkSuggestionService $service, ServerRequestInterface $request, CreateLinkForm $form): void
    {
        $this->validateLinkForm($form, (array)$request->getParsedBody());
        $service->createSuggestion($form->withSource('api'), $this->getIdentityFromRequest($request));
    }

    public function delete(LinkSuggestionService $service, ServerRequestInterface $request, FindLinkForm $form): void
    {
        $this->validateLinkForm($form, $request->getQueryParams());
        $service->deleteSuggestion($form->getUrl(), $this->getIdentityFromRequest($request));
    }

    private function validateLinkForm(FormModel $form, array $data): void
    {
        $form->load($data);
        if (!$form->validate($this->validator)) {
            throw new HttpException(Status::BAD_REQUEST, current($form->firstErrors()));
        }
    }
}
