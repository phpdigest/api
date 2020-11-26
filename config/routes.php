<?php

declare(strict_types=1);

use App\Api\External\Controller\LinkController;
use App\Api\External\Controller\UserController;
use App\Api\Telegram\Controller\WebhookAction;
use App\Api\UI;
use roxblnfk\SmartStream\Middleware\BucketStreamMiddleware;
use Yiisoft\Auth\Middleware\Authentication;
use Yiisoft\Csrf\CsrfMiddleware;
use Yiisoft\Http\Method;
use Yiisoft\Request\Body\RequestBodyParser;
use Yiisoft\Router\Group;
use Yiisoft\Router\Route;
use Yiisoft\Session\SessionMiddleware;
use Yiisoft\User\AutoLoginMiddleware;

return [
    // UI
    Group::create(
        '',
        [
            Route::get('/', [UI\Controller\SiteController::class, 'index'])->name('site/index'),
            Route::get('/tables', [UI\Controller\SiteController::class, 'tables'])->name('data/tables'),
            Route::methods([Method::GET, Method::POST], '/register', [UI\Controller\UserController::class, 'register'])
                ->name('user/register'),
            Route::methods([Method::GET, Method::POST], '/login', [UI\Controller\UserController::class, 'login'])
                ->name('user/login'),
            Route::post('/logout', [UI\Controller\UserController::class, 'logout'])
                ->name('user/logout'),
            Route::get('/link', [UI\Controller\LinkController::class, 'form'])
                ->name('link/form'),
            Route::post('/link', [UI\Controller\LinkController::class, 'share'])
                ->name('link/share'),
        ]
    )
        // ->addMiddleware(AutoLoginMiddleware::class)
        ->addMiddleware(SessionMiddleware::class)
        ->addMiddleware(CsrfMiddleware::class),

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
