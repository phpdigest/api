<?php

declare(strict_types=1);

namespace App\Api\UI\Controller;

use App\Api\UI\Form\ShareLinkForm;
use App\Api\UI\Widget\FlashMessage;
use App\Module\Link\Api\UserLinkService;
use App\Module\User\Api\IdentityFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\Http\Header;
use Yiisoft\Http\Status;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Session\Flash\FlashInterface;
use Yiisoft\User\GuestIdentity;
use Yiisoft\User\User;

final class LinkController extends AbstractController
{
    public function form(ShareLinkForm $form): ResponseInterface {
        return $this->render('link/form', ['form' => $form]);
    }

    public function share(
        ServerRequestInterface $request,
        FlashInterface $flash,
        UrlGeneratorInterface $url,
        ShareLinkForm $form,
        IdentityFactory $identityFactory,
        User $user,
        UserLinkService $service
    ): ResponseInterface {
        if (!$form->load($request->getParsedBody()) || !$form->validate()) {
            $flash->add(FlashMessage::WARNING, ['header' => 'Fail', 'body' => 'Check the input is correct.'], true);
            return $this->render('link/form', ['form' => $form]);
        }

        try {
            $identity = $user->getIdentity();
            if ($identity instanceof GuestIdentity) {
                $identity = $identityFactory->createIdentity();
                $user->login($identity);
                # Inform user about creating an identity
                $flash->add(FlashMessage::INFO, [
                    'header' => 'Note',
                    'body' => 'We have created an anonymous account for you and your session.'
                ], true);
            }
            $service->createLink($form, $identity);
            $success = $user->login($identity);
        } catch (\Exception $e) {
            $success = false;
        }

        if (!$success) {
            $flash->add(FlashMessage::DANGER, ['header' => 'Error', 'body' => 'Something wrong.'], true);
            return $this->render('link/form', ['form' => $form]);
        }

        $flash->add(FlashMessage::SUCCESS, ['header' => 'Link has been added', 'body' => 'Thank you for your contribution.'], true);
        return $this->responseFactory
            ->createResponse(Status::FOUND)
            ->withHeader(Header::LOCATION, $url->generate('link/form'));
    }
}
