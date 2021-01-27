<?php

declare(strict_types=1);

namespace App\Api\UI\Controller;

use App\Api\UI\Controller\Admin;
use App\Api\UI\Form\LoginForm;
use App\Api\UI\Form\RegisterForm;
use App\Api\UI\Widget\FlashMessage;
use App\Module\User\Api\AuthClassic;
use App\Module\User\Api\RegisterClassic;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\Http\Header;
use Yiisoft\Http\Method;
use Yiisoft\Http\Status;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Session\Flash\FlashInterface;
use Yiisoft\User\User;
use Yiisoft\Validator\ValidatorInterface;

class UserController extends AbstractController
{
    public const ROUTE_PREFIX = 'user__';
    public const PAGE_REGISTER = self::ROUTE_PREFIX . 'register';
    public const ACTION_REGISTER = self::ROUTE_PREFIX . 'register_action';
    public const ACTION_LOGOUT = self::ROUTE_PREFIX . 'logout_action';
    public const PAGE_LOGIN = self::ROUTE_PREFIX . 'login';
    public const ACTION_LOGIN = self::ROUTE_PREFIX . 'login_action';

    /**
     * Action for {@see P_REGISTER}
     */
    public function pageRegister(RegisterForm $form): ResponseInterface
    {
        return $this->render('user/register', ['form' => $form]);
    }

    /**
     * Action for {@see A_REGISTER}
     */
    public function actionRegister(
        ServerRequestInterface $request,
        FlashInterface $flash,
        UrlGeneratorInterface $url,
        RegisterForm $form,
        RegisterClassic $registerClassic,
        User $user,
        ValidatorInterface $validator
    ): ResponseInterface {
        $body = $request->getParsedBody();

        if ($form->load($body) && $form->validate($validator)) {
            try {
                $identity = $registerClassic->register(
                    $form->getAttributeValue('username'),
                    $form->getAttributeValue('password')
                );
                $success = $user->login($identity);
            } catch (\Exception $e) {
                $flash->add(FlashMessage::DANGER, ['header' => 'Error', 'body' => $e->getMessage()], true);
                $success = false;
            }

            if ($success) {
                $flash->add(FlashMessage::SUCCESS, ['header' => 'Good job!', 'body'   => 'You are bro now.'], true);
                return $this->responseFactory
                    ->createResponse(Status::FOUND)
                    ->withHeader(Header::LOCATION, $url->generate(Admin\CommonController::PAGE_INDEX));
            }

            $flash->add(FlashMessage::WARNING, ['header' => 'Fail!', 'body' => 'Registration failed.'], true);
        }

        return $this->pageRegister($form);
    }

    /**
     * Action for {@see P_LOGIN}
     */
    public function pageLogin(LoginForm $form): ResponseInterface
    {
        return $this->render('user/login', ['form' => $form]);
    }

    /**
     * Action for {@see A_LOGIN}
     */
    public function actionLogin(
        ServerRequestInterface $request,
        FlashInterface $flash,
        UrlGeneratorInterface $url,
        LoginForm $form,
        AuthClassic $authClassic,
        User $user,
        ValidatorInterface $validator
    ): ResponseInterface {
        $body = $request->getParsedBody();
        $method = $request->getMethod();

        if (($method === Method::POST) && $form->load($body) && $form->validate($validator)) {
            try {
                $identity = $authClassic->logIn(
                    $form->getAttributeValue('username'),
                    $form->getAttributeValue('password')
                );
                $success = $user->login($identity);
            } catch (\Exception $e) {
                $success = false;
            }

            if (!$success) {
                $flash->add(FlashMessage::WARNING, ['header' => 'Fail!', 'body' => 'Check the input is correct.'], true);
                return $this->pageLogin($form);
            }

            $flash->add(FlashMessage::SUCCESS, ['header' => 'Hello!', 'body'   => 'You are welcome.'], true);
            return $this->responseFactory
                ->createResponse(Status::FOUND)
                ->withHeader(Header::LOCATION, $url->generate(Admin\CommonController::PAGE_INDEX));
        }
        return $this->pageLogin($form);
    }

    /**
     * Action for {@see A_LOGOUT}
     */
    public function actionLogout(
        UrlGeneratorInterface $url,
        User $user
    ): ResponseInterface {
        $user->logout(true);
        return $this->responseFactory
            ->createResponse(Status::FOUND)
            ->withHeader(Header::LOCATION, $url->generate(SiteController::PAGE_INDEX));
    }
}
