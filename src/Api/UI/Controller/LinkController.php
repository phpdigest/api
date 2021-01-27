<?php

declare(strict_types=1);

namespace App\Api\UI\Controller;

use App\Api\UI\Form\SuggestLinkForm;
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
use Yiisoft\Validator\ValidatorInterface;

final class LinkController extends AbstractController
{
    public const ROUTE_PREFIX = 'link__';
    public const PAGE_SUGGEST_LINK = self::ROUTE_PREFIX . 'suggest_link';
    public const ACTION_SUGGEST_LINK = self::ROUTE_PREFIX . 'suggest_link_action';

    /**
     * Action for {@see P_SUGGEST_LINK}
     */
    public function pageSuggestLink(SuggestLinkForm $form): ResponseInterface {
        return $this->render('link/form', ['form' => $form]);
    }

    /**
     * Action for {@see A_SUGGEST_LINK}
     */
    public function actionSuggestLink(
        ServerRequestInterface $request,
        FlashInterface $flash,
        UrlGeneratorInterface $url,
        SuggestLinkForm $form,
        IdentityFactory $identityFactory,
        User $user,
        UserLinkService $service,
        ValidatorInterface $validator
    ): ResponseInterface {
        if (!$form->load($request->getParsedBody()) || !$form->validate($validator)) {
            $flash->add(FlashMessage::WARNING, ['header' => 'Fail', 'body' => 'Check the input is correct.'], true);
            return $this->pageSuggestLink($form);
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
            $service->createSuggestion($form, $identity);
            $success = $user->login($identity);
        } catch (\Exception $e) {
            $success = false;
        }

        if (!$success) {
            $flash->add(FlashMessage::DANGER, ['header' => 'Error', 'body' => 'Something wrong.'], true);
            return $this->pageSuggestLink($form);
        }

        $flash->add(FlashMessage::SUCCESS, ['header' => 'Link has been added', 'body' => 'Thank you for your contribution.'], true);
        return $this->responseFactory
            ->createResponse(Status::FOUND)
            ->withHeader(Header::LOCATION, $url->generate(self::PAGE_SUGGEST_LINK));
    }
}
