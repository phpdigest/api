<?php

declare(strict_types=1);

use App\Api\UI\Controller\UserController;
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

<div class="row">
    <div class="card col-md-6 offset-md-3">
        <div class="card-body">
            <h3 class="card-title">Registration</h3>
            <?= Form::widget()
                    ->action($url->generate(UserController::ACTION_REGISTER))
                    ->options(
                        [
                            'id' => 'form-register',
                            'csrf' => $csrf,
                            'enctype' => 'multipart/form-data',
                        ]
                    )
                    ->begin() ?>

            <?= $field->config($form, 'username') ?>
            <?= $field->config($form, 'password')->passwordInput() ?>


            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>

            <?= Form::end() ?>

        </div>
    </div>
</div>
