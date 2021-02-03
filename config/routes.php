<?php

declare(strict_types=1);

use App\Api\External\Controller\LinkController;
use App\Api\External\Controller\UserController;
use App\Api\Telegram\Controller\WebhookAction;
use App\Api\UI;
use App\Api\UI\Controller\Admin;
use App\Module\Rbac\Middleware\PermissionMiddleware;
use roxblnfk\SmartStream\Middleware\BucketStreamMiddleware;
use Yiisoft\Auth\Middleware\Authentication;
use Yiisoft\Csrf\CsrfMiddleware;
use Yiisoft\Request\Body\RequestBodyParser;
use Yiisoft\Router\Group;
use Yiisoft\Router\Route;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Session\SessionMiddleware;
use Yiisoft\User\AutoLoginMiddleware;

return [
    // UI
    Group::create(
        '',
        [
            // Site
            Route::get('/', [UI\Controller\SiteController::class, 'pageIndex'])
                ->name(UI\Controller\SiteController::PAGE_INDEX),
            // User
            Route::get('/register', [UI\Controller\UserController::class, 'pageRegister'])
                ->name(UI\Controller\UserController::PAGE_REGISTER),
            Route::post('/register', [UI\Controller\UserController::class, 'actionRegister'])
                ->name(UI\Controller\UserController::ACTION_REGISTER),
            Route::get('/login', [UI\Controller\UserController::class, 'pageLogin'])
                ->name(UI\Controller\UserController::PAGE_LOGIN),
            Route::post('/login', [UI\Controller\UserController::class, 'actionLogin'])
                ->name(UI\Controller\UserController::ACTION_LOGIN),
            Route::post('/logout', [UI\Controller\UserController::class, 'actionLogout'])
                ->name(UI\Controller\UserController::ACTION_LOGOUT),
            // Link
            Route::get('/link', [UI\Controller\LinkController::class, 'pageSuggestLink'])
                ->name(UI\Controller\LinkController::PAGE_SUGGEST_LINK),
            Route::post('/link', [UI\Controller\LinkController::class, 'actionSuggestLink'])
                ->name(UI\Controller\LinkController::ACTION_SUGGEST_LINK),

            // Admin
            Group::create('/admin', [
                Route::get('/index', [Admin\CommonController::class, 'pageIndex'])
                    ->name(Admin\CommonController::PAGE_INDEX),
                Group::create('/link', [
                    Route::get('/suggestions[/{page:\d}]', [Admin\LinkController::class, 'pageSuggestionTable'])
                        ->name(Admin\LinkController::PAGE_SUGGESTION_TABLE),
                    Route::get('/urls[/{page:\d}]', [Admin\LinkController::class, 'pageUrlTable'])
                        ->name(Admin\LinkController::PAGE_URL_TABLE),
                ]),
                Group::create('/user', [
                    Route::get('/accounts[/{page:\d}]', [Admin\UserController::class, 'pageAccountTable'])
                        ->name(Admin\UserController::PAGE_ACCOUNT_TABLE),
                ]),
            ])->addMiddleware(static fn (PermissionMiddleware $mw, UrlGeneratorInterface $urlGenerator) => $mw
                ->withPermission('admin_panel')
                ->withRedirection($urlGenerator->generate(UI\Controller\SiteController::PAGE_INDEX))),
        ]
    )
        // todo add after https://github.com/yiisoft/user/issues/11
        // ->addMiddleware(AutoLoginMiddleware::class)
        ->addMiddleware(CsrfMiddleware::class)
        ->addMiddleware(SessionMiddleware::class),

    // External API
    Group::create(
        '/api',
        [
            Route::get('/user', UserController::class)->name('api/user/get')->addMiddleware(Authentication::class),
            Route::post('/user', UserController::class)->name('api/user/post'),
            Route::delete('/user', UserController::class)->name('api/user/delete'),
            Route::put('/user', UserController::class)->name('api/user/put'),
            Route::patch('/user', UserController::class)->name('api/user/patch'),
            Route::options('/user', UserController::class)->name('api/user/options'),
            Route::head('/user', UserController::class)->name('api/user/head'),

            Route::get('/link', LinkController::class)->name('api/link/get')->addMiddleware(Authentication::class),
            Route::post('/link', LinkController::class)->name('api/link/post')->addMiddleware(Authentication::class),
            Route::delete('/link', LinkController::class)->name('api/link/delete')->addMiddleware(Authentication::class),
            Route::put('/link', LinkController::class)->name('api/link/put'),
            Route::patch('/link', LinkController::class)->name('api/link/patch'),
            Route::options('/link', LinkController::class)->name('api/link/options'),
            Route::head('/link', LinkController::class)->name('api/link/head'),
        ]
    )->addMiddleware(BucketStreamMiddleware::class)
        ->addMiddleware(RequestBodyParser::class),

    // Telegram
    Route::post('/telegram', [WebhookAction::class, '__invoke'])->name('telegram/webhook'),
];
