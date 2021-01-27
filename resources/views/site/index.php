<?php

declare(strict_types=1);

/** @var Yiisoft\Router\UrlGeneratorInterface $url */
/** @var string|null $csrf */
/** @var \App\Common\Application\ApplicationParameters $applicationParameters */

use App\Api\UI\Controller\LinkController;

$this->params['breadcrumbs'] = '/';

$this->setTitle($applicationParameters->getName());
?>

<h1 class="display-2 mb-4 text-center">Hello</h1>

<div class="row row-cols-md-3">
    <div>
        <div class="card">
            <div class="bg-light text-center pt-2">
                <i class="fas fa-search fa-1x align-middle"></i>
                <i class="fas fa-search fa-3x align-middle"></i>
                <i class="fas fa-search fa-6x align-middle"></i>
            </div>
            <div class="card-body">
                <div class="card-title">Search in all digests</div>
                <a href="https://pronskiy.com/php-digest/">pronskiy.com/php-digest</a>
            </div>
        </div>
    </div>

    <div>
        <div class="card">
            <div class="bg-light text-center pt-2">
                <i class="fas fa-plus fa-2x"></i>
                <i class="fas fa-plus fa-6x"></i>
                <i class="fas fa-plus fa-2x"></i>
            </div>
            <div class="card-body">
                <div class="card-title">Suggest link</div>
                <div><a href="<?= $url->generate(LinkController::PAGE_SUGGEST_LINK) ?>"><i class="fas fa-link"></i> This site</a></div>
                <div><a href="https://t.me/phpdigest_bot/"><i class="fab fa-telegram-plane"></i> Telegram</a></div>
                <div><a href="https://bit.ly/php-digest-add-link"><i class="fas fa-list"></i> Google forms</a></div>
            </div>
        </div>
    </div>
</div>
