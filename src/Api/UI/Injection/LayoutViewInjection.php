<?php

declare(strict_types=1);

namespace App\Api\UI\Injection;

use Yiisoft\Access\AccessCheckerInterface;
use Yiisoft\Router\UrlMatcherInterface;
use Yiisoft\Yii\View\LayoutParametersInjectionInterface;
use Yiisoft\User\CurrentUser;

class LayoutViewInjection implements LayoutParametersInjectionInterface
{
    private CurrentUser $user;
    private UrlMatcherInterface $urlMatcher;
    private AccessCheckerInterface $accessChecker;

    public function __construct(
        CurrentUser $user,
        UrlMatcherInterface $urlMatcher,
        AccessCheckerInterface $accessChecker
    ) {
        $this->user = $user;
        $this->urlMatcher = $urlMatcher;
        $this->accessChecker = $accessChecker;
    }

    public function getLayoutParameters(): array
    {
        return [
            'user' => $this->user->getIdentity(),
            'currentUrl' => (string)$this->urlMatcher->getCurrentUri(),
            'accessChecker' => $this->accessChecker,
        ];
    }
}
