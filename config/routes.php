<?php

declare(strict_types=1);

use App\Api\UI\Controller\ContactController;
use App\Api\UI\Controller\SiteController;
use Yiisoft\Http\Method;
use Yiisoft\Router\Group;
use Yiisoft\Router\Route;

return [
    Route::get('/', [SiteController::class, 'index'])->name('site/index'),
    Route::get('/about', [SiteController::class, 'about'])->name('site/about'),
    Route::methods([Method::GET, Method::POST], '/contact', [ContactController::class, 'contact'])
        ->name('contact/form'),
    // External API
    Group::create('/api', [
        Route::anyMethod('/link', \App\Api\External\Controller\LinkController::class)->name('api/link'),
        Route::anyMethod('/user', \App\Api\External\Controller\UserController::class)->name('api/user'),

    ])->addMiddleware(\roxblnfk\SmartStream\Middleware\BucketStreamMiddleware::class),
];
