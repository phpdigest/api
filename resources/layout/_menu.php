<?php

declare(strict_types=1);

use Yiisoft\Yii\Bootstrap5\Nav;
use Yiisoft\Yii\Bootstrap5\NavBar;

/**
 * @var App\Common\Application\ApplicationParameters $applicationParameters
 * @var Yiisoft\Router\UrlGeneratorInterface $url
 * @var Yiisoft\Router\UrlMatcherInterface $urlMatcher
 * @var \Yiisoft\Auth\IdentityInterface $user
 * @var string $csrf
 */

$currentUrl = $url->generate($urlMatcher->getCurrentRoute()->getName());
echo NavBar::widget()
    ->brandLabel($applicationParameters->getName())
    ->brandImage('/images/digest-logo.png')
    ->options(['class' => 'navbar navbar-dark bg-dark navbar-expand-sm text-white'])
    ->innerContainerOptions(['class' => 'container-fluid'])
    ->begin();

$mainMenuButtons = [
    ['label' => 'Share link', 'url' => $url->generate('link/form')],
];
if ($user instanceof \Yiisoft\User\GuestIdentity) {
    $userButtons = [
        ['label' => 'Register', 'url' => $url->generate('user/register')],
        ['label' => 'Login', 'url' => $url->generate('user/login')]
    ];
} else {
    $exitUrl = $url->generate('user/logout');
    $exitButton = <<<HTML
        <form method="post" action="{$exitUrl}">
            <input hidden name="_csrf" value="{$csrf}">
            <label>Exit<button hidden></button></label>
        </form>
        HTML;
    $userButtons = [
        ['label' => $exitButton, 'encode' => false]
    ];
    $mainMenuButtons[] = ['label' => 'Data', 'url' => $url->generate('data/tables')];
}

echo Nav::widget()
    ->currentPath($currentUrl)
    ->options(['class' => 'navbar-nav mx-auto'])
    ->items($mainMenuButtons),
Nav::widget()
    ->currentPath($currentUrl)
    ->options(['class' => 'navbar-nav'])
    ->items($userButtons);

echo NavBar::end();
