<?php

declare(strict_types=1);

use Yiisoft\Yii\Bulma\Nav;
use Yiisoft\Yii\Bulma\NavBar;

/**
 * @var App\Common\Application\ApplicationParameters $applicationParameters
 * @var Yiisoft\Router\UrlGeneratorInterface $url
 * @var Yiisoft\Router\UrlMatcherInterface $urlMatcher
 * @var \Yiisoft\Auth\IdentityInterface $user
 * @var string $csrf
 */

$currentUrl = $url->generate($urlMatcher->getCurrentRoute()->getName());
?>

<?= NavBar::begin()
    ->brandLabel($applicationParameters->getName())
    ->brandImage('/images/yii-logo.jpg')
    ->options(['class' => 'is-dark', 'data-sticky' => '', 'data-sticky-shadow' => ''])
    ->itemsOptions(['class' => 'navbar-end is-dark'])
    ->start();
?>
    <?php
    if ($user instanceof \Yiisoft\User\GuestIdentity) {
        $userButtons = [
            ['label' => 'Register', 'url' => $url->generate('user/register')],
            ['label' => 'Login', 'url' => $url->generate('user/login')]
        ];
    } else {
        $exitUrl = $url->generate('user/logout');
        $exitButton = <<<FORM
            <form method="post" action="{$exitUrl}">
                <input hidden name="_csrf" value="{$csrf}">
                <label>Exit<button hidden></button></label>
            </form>
            FORM;
        $userButtons = [
            ['label' => $exitButton, 'encode' => false]
        ];
    }
    ?>
    <?= Nav::widget()
        ->currentPath($currentUrl)
        ->items([
            ['label' => 'Share link', 'url' => $url->generate('link/form')],
            ...$userButtons
        ]) ?>

<?= NavBar::end();
