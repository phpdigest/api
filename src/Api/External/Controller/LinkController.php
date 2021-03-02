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
use Yiisoft\Validator\ValidatorInterface;

final class LinkController extends ApiController
{
    public function get(
        ValidatorInterface $validator,
        LinkSuggestionService $service,
        ServerRequestInterface $request,
        FindLinkForm $form
    ): array {
        $this->validateLinkForm($validator, $form, $request->getQueryParams());
        $link = $service->findSuggestion($form->getUrl(), $this->getIdentityFromRequest($request));

        return ['url' => $link->url->__toString()];
    }

    public function post(
        ValidatorInterface $validator,
        LinkSuggestionService $service,
        ServerRequestInterface $request,
        CreateLinkForm $form
    ): void {
        $this->validateLinkForm($validator, $form, (array)$request->getParsedBody());
        $service->createSuggestion($form->withSource('api'), $this->getIdentityFromRequest($request));
    }

    public function delete(
        ValidatorInterface $validator,
        LinkSuggestionService $service,
        ServerRequestInterface $request,
        FindLinkForm $form
    ): void {
        $this->validateLinkForm($validator, $form, $request->getQueryParams());
        $service->deleteSuggestion($form->getUrl(), $this->getIdentityFromRequest($request));
    }

    private function validateLinkForm(ValidatorInterface $validator, FormModel $form, array $data): void
    {
        $form->load($data);
        if (!$validator->validate($form)) {
            throw new HttpException(Status::BAD_REQUEST, current($form->getFirstErrors()));
        }
    }
}
