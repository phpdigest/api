<?php

declare(strict_types=1);

use App\Api\UI\Controller\Admin\CommonController;
use App\Api\UI\Controller\LinkController;
use App\Api\UI\Controller\UserController;
use Yiisoft\Access\AccessCheckerInterface;
use Yiisoft\Yii\Bootstrap5\Nav;
use Yiisoft\Yii\Bootstrap5\NavBar;

/**
 * @var App\Common\Application\ApplicationParameters $applicationParameters
 * @var Yiisoft\Router\UrlGeneratorInterface $url
 * @var AccessCheckerInterface $accessChecker
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
    ['label' => 'Suggest link', 'url' => $url->generate(LinkController::PAGE_SUGGEST_LINK)],
];
if ($user instanceof \Yiisoft\User\GuestIdentity) {
    $userButtons = [
        ['label' => 'Register', 'url' => $url->generate(UserController::PAGE_REGISTER)],
        ['label' => 'Login', 'url' => $url->generate(UserController::PAGE_LOGIN)]
    ];
} else {
    $exitUrl = $url->generate(UserController::ACTION_LOGOUT);
    $exitButton = <<<HTML
        <form method="post" action="{$exitUrl}">
            <input hidden name="_csrf" value="{$csrf}">
            <label>Exit<button hidden></button></label>
        </form>
        HTML;
    $userButtons = [
        ['label' => $exitButton, 'encode' => false]
    ];
    if ($accessChecker->userHasPermission($user->getId(), 'admin_panel')) {
        $mainMenuButtons[] = ['label' => 'Admin', 'url' => $url->generate(CommonController::PAGE_INDEX)];
    }
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
