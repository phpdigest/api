<?php

declare(strict_types=1);

use App\Api\UI\Controller\Admin\LinkController;
use Yiisoft\Html\Html;

/**
 * @var Yiisoft\Assets\AssetManager $assetManager
 * @var Yiisoft\Router\UrlGeneratorInterface $url
 * @var string|null $csrf
 * @var Yiisoft\Form\Widget\Field $field
 * @var \Yiisoft\Auth\IdentityInterface $user
 */

$this->params['breadcrumbs'] = 'Data tables';

$this->setTitle('Data tables');

echo Html::a('Suggested links', $url->generate(LinkController::PAGE_SUGGESTION_TABLE));
