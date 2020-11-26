<?php

declare(strict_types=1);

namespace App\Api\UI\Controller;

use App\Api\UI\Form\LoginForm;
use App\Api\UI\Form\RegisterForm;
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

class UserController extends AbstractController
{
    public function register(
        ServerRequestInterface $request,
        FlashInterface $flash,
        UrlGeneratorInterface $url,
        RegisterForm $form,
        RegisterClassic $registerClassic,
        User $user
    ): ResponseInterface {
        $body = $request->getParsedBody();
        $method = $request->getMethod();

        if (($method === Method::POST) && $form->load($body) && $form->validate()) {
            try {
                $identity = $registerClassic->register(
                    $form->getAttributeValue('login'),
                    $form->getAttributeValue('password')
                );
                $success = $user->login($identity);
            } catch (\Exception $e) {
                $flash->add('is-danger', ['header' => 'Error', 'body' => $e->getMessage()], true);
                $success = false;
            }

            if ($success) {
                $flash->add('is-success', ['header' => 'Good job!', 'body'   => 'You are bro now.'], true);
                return $this->responseFactory
                    ->createResponse(Status::FOUND)
                    ->withHeader(Header::LOCATION, $url->generate('data/tables'));
            }

            $flash->add('is-warning', ['header' => 'Fail!', 'body' => 'Registration failed.'], true);
        }

        return $this->render('user/register', ['form' => $form]);
    }

    public function login(
        ServerRequestInterface $request,
        FlashInterface $flash,
        UrlGeneratorInterface $url,
        LoginForm $form,
        AuthClassic $authClassic,
        User $user
    ): ResponseInterface {
        $body = $request->getParsedBody();
        $method = $request->getMethod();

        if (($method === Method::POST) && $form->load($body) && $form->validate()) {
            try {
                $identity = $authClassic->login(
                    $form->getAttributeValue('login'),
                    $form->getAttributeValue('password')
                );
                $success = $user->login($identity);
            } catch (\Exception $e) {
                $success = false;
            }

            if (!$success) {
                $flash->add('is-warning', ['header' => 'Fail!', 'body' => 'Check the input is correct.'], true);
                return $this->renderLoginPage($form);
            }

            $flash->add('is-success', ['header' => 'Hello!', 'body'   => 'You are welcome.'], true);
            return $this->responseFactory
                ->createResponse(Status::FOUND)
                ->withHeader(Header::LOCATION, $url->generate('data/tables'));
        }
        return $this->renderLoginPage($form);
    }

    public function logout(
        UrlGeneratorInterface $url,
        User $user
    ): ResponseInterface {
        $user->logout(true);
        return $this->responseFactory
            ->createResponse(Status::FOUND)
            ->withHeader(Header::LOCATION, $url->generate('site/index'));
    }

    private function renderLoginPage(LoginForm $form): ResponseInterface
    {
        return $this->render('user/login', ['form' => $form]);
    }
}
