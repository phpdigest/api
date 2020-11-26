<?php

declare(strict_types=1);

namespace App\Api\External\Controller;

use App\Api\External\Exception\HttpException;
use App\Module\Link\Api\UserLinkService;
use App\Api\Common\Form\CreateLinkForm;
use App\Api\Common\Form\FindLinkForm;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\Form\FormModel;
use Yiisoft\Http\Status;

final class LinkController extends ApiController
{
    public function get(UserLinkService $service, ServerRequestInterface $request, FindLinkForm $form): array
    {
        $this->validateLinkForm($form, $request->getQueryParams());
        $link = $service->getLink($form->getUrl(), $this->getIdentityFromRequest($request));

        return ['url' => $link->getUrl()];
    }

    public function post(UserLinkService $service, ServerRequestInterface $request, CreateLinkForm $form): void
    {
        $this->validateLinkForm($form, (array)$request->getParsedBody());
        $service->createLink($form, $this->getIdentityFromRequest($request));
    }

    public function delete(UserLinkService $service, ServerRequestInterface $request, FindLinkForm $form): void
    {
        $this->validateLinkForm($form, $request->getQueryParams());
        $service->deleteLink($form->getUrl(), $this->getIdentityFromRequest($request));
    }

    private function validateLinkForm(FormModel $form, array $data): void
    {
        $form->load($data);
        if (!$form->validate()) {
            throw new HttpException(Status::BAD_REQUEST, current($form->firstErrors()));
        }
    }
}
