<?php

declare(strict_types=1);

use Yiisoft\Form\Widget\Form;
use Yiisoft\Html\Html;

/**
 * @var Yiisoft\Router\UrlGeneratorInterface $url
 * @var string|null $csrf
 * @var \App\Api\UI\Form\RegisterForm $form
 * @var Yiisoft\Form\Widget\Field $field
 */

$this->params['breadcrumbs'] = 'Registration';

$this->setTitle('Registration');

?>
<div class="column is-4 is-offset-4">

    <p class="subtitle has-text-black">
        Registration
    </p>

    <?= Form::widget()
            ->action($url->generate('user/register'))
            ->options(
                [
                    'id' => 'form-register',
                    'csrf' => $csrf,
                    'enctype' => 'multipart/form-data',
                ]
            )
            ->begin() ?>

    <?= $field->config($form, 'login') ?>
    <?= $field->config($form, 'password') ?>


    <?= Html::submitButton(
        'Submit',
        [
            'class' => 'button is-block is-info is-fullwidth has-margin-top-15',
            'id' => 'contact-button',
            'tabindex' => '5'
        ]
    ) ?>

    <?= Form::end() ?>

</div>
