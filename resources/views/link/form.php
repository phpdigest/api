<?php

declare(strict_types=1);

use Yiisoft\Form\Widget\Form;
use Yiisoft\Html\Html;

/* @var App\Api\UI\Form\ShareLinkForm $form */
/* @var Yiisoft\Router\UrlGeneratorInterface $url */
/* @var string|null $csrf */
/* @var Yiisoft\Form\Widget\Field $field */

$this->setTitle('Share link');
?>

<div class="row">
    <div class="card col-md-6 offset-md-3">
        <div class="card-body">
            <h3 class="card-title">Share link</h3>
            <p>Please fill out the following fields</p>
            <?= Form::widget()
                ->action($url->generate('link/share'))
                ->options(['id' => $form->formName(), 'csrf' => $csrf])
                ->begin() ?>

                <?= $field->config($form, 'url') ?>
                <?= $field->config($form, 'description')
                    ->textArea(['class' => 'form-control textarea', 'rows' => 2]) ?>

                <?= Html::submitButton(
                    'Share ' . html::tag('i', '', ['class' => 'fas fa-share', 'aria-hidden' => 'true']),
                    ['class' => 'btn btn-primary']
                ) ?>
            <?= Form::end() ?>
        </div>
    </div>
</div>
