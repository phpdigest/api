<?php

declare(strict_types=1);

use App\Api\UI\Asset\AppAsset;
use App\Api\UI\Asset\CdnFontAwesomeAsset;
use App\Api\UI\Widget\FlashMessage;
use Yiisoft\Access\AccessCheckerInterface;
use Yiisoft\Html\Html;

/**
 * @var App\Common\Application\ApplicationParameters $applicationParameters
 * @var Yiisoft\Assets\AssetManager $assetManager
 * @var AccessCheckerInterface $accessChecker
 * @var Yiisoft\View\WebView $this
 * @var string $content
 * @var \Yiisoft\Auth\IdentityInterface $user
 * @var string $csrf
 */

$assetManager->register([
    AppAsset::class,
    CdnFontAwesomeAsset::class,
]);

$this->setCssFiles($assetManager->getCssFiles());
$this->setJsFiles($assetManager->getJsFiles());

$this->beginPage()

?><!DOCTYPE html>
<html lang="<?= Html::encode($applicationParameters->getLanguage()) ?>" class="h-100">
    <?= $this->render('_head') ?>
    <?php $this->beginBody() ?>
    <body class="d-flex flex-column h-100">
        <header>
            <?= $this->render('_menu', ['user' => $user, 'csrf' => $csrf, 'accessChecker' => $accessChecker]) ?>
        </header>
        <main class="flex-shrink-0 flex-fill">
            <div class="container-xl pt-3 h-100">
                <?= FlashMessage::widget() ?>
                <?= $content ?>
            </div>
        </main>
        <?= $this->render('_footer') ?>
    </body>
    <?php $this->endBody() ?>
</html>
<?php $this->endPage() ?>
