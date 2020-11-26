<?php

declare(strict_types=1);

use App\Api\UI\Asset\AppAsset;
use App\Api\UI\Asset\CdnFontAwesomeAsset;
use App\Api\UI\Widget\FlashMessage;
use Yiisoft\Html\Html;

/**
 * @var App\Common\Application\ApplicationParameters $applicationParameters
 * @var Yiisoft\Assets\AssetManager $assetManager
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
<html lang="<?= Html::encode($applicationParameters->getLanguage()) ?>">
    <?= $this->render('_head') ?>
    <?php $this->beginBody() ?>
        <body>
            <section class="hero is-fullheight is-dark">
                <div class="hero-head has-background-black">
                    <?= $this->render('_menu', ['user' => $user, 'csrf' => $csrf]) ?>
                </div>
                <?= FlashMessage::widget() ?>
                <div class="hero-body">
                    <div class="container has-text-centered">
                        <?= $content ?>
                    </div>
                </div>
                <div class="hero-footer has-background-black is-dark">
                    <?= $this->render('_footer') ?>
                </div>
            </section>
        </body>

    <?php $this->endBody() ?>
</html>
<?php $this->endPage() ?>
