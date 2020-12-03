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

<div class="columns is-centered">
    <div class="column is-7-tablet is-6-desktop">
        <article class="tile is-child notification is-black">
            <p class="title">Share link</p>
            <p class="subtitle">Please fill out the following fields</p>
            <?= Form::widget()
                ->action($url->generate('link/share'))
                ->options(['id' => $form->formName(), 'csrf' => $csrf])
                ->begin() ?>

                <?= $field->config($form, 'url') ?>
                <?= $field->config($form, 'description')
                    ->textArea(['class' => 'form-control textarea', 'rows' => 2]) ?>

                <?= Html::submitButton(
                    'Share ' . html::tag('i', '', ['class' => 'fas fa-share', 'aria-hidden' => 'true']),
                    [
                        'class' => 'button is-block is-info is-fullwidth has-margin-top-15',
                        'id' => 'contact-button',
                        'tabindex' => '5'
                    ]
                ) ?>
            <?= Form::end() ?>
        </article>
    </div>
</div>
