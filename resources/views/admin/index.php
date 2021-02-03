<?php

declare(strict_types=1);

use App\Api\UI\Controller\Admin\LinkController;
use App\Api\UI\Controller\Admin\UserController;
use Yiisoft\Html\Html;

/**
 * @var Yiisoft\Assets\AssetManager $assetManager
 * @var Yiisoft\Router\UrlGeneratorInterface $url
 * @var string|null $csrf
 * @var Yiisoft\Form\Widget\Field $field
 * @var \Yiisoft\Auth\IdentityInterface $user
 */

$this->params['breadcrumbs'] = 'Index';

$this->setTitle('Index');

?>
<ul>
    <li><?= Html::a('URLs', $url->generate(LinkController::PAGE_URL_TABLE)) ?></li>
    <li><?= Html::a('Suggestions', $url->generate(LinkController::PAGE_SUGGESTION_TABLE)) ?></li>
    <li><?= Html::a('Accounts', $url->generate(UserController::PAGE_ACCOUNT_TABLE)) ?></li>
</ul>


