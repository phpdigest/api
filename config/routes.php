<?php

declare(strict_types=1);

use App\Api\External\Controller\LinkController;
use App\Api\External\Controller\UserController;
use App\Api\Telegram\Controller\WebhookAction;
use App\Api\UI\Controller\ContactController;
use App\Api\UI\Controller\SiteController;
use roxblnfk\SmartStream\Middleware\BucketStreamMiddleware;
use Yiisoft\Auth\Middleware\Authentication;
use Yiisoft\Http\Method;
use Yiisoft\Request\Body\RequestBodyParser;
use Yiisoft\Router\Group;
use Yiisoft\Router\Route;

return [
    // UI
    Route::get('/', [SiteController::class, 'index'])->name('site/index'),
    Route::get('/about', [SiteController::class, 'about'])->name('site/about'),
    Route::methods([Method::GET, Method::POST], '/contact', [ContactController::class, 'contact'])
        ->name('contact/form'),

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
