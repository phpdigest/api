<?php

declare(strict_types=1);

namespace App\Api\UI\Form;

use Yiisoft\Form\FormModel;
use Yiisoft\Validator\Rule\Required;

final class RegisterForm extends FormModel
{
    private string $username = '';
    private string $password = '';

    public function attributeLabels(): array
    {
        return [
            'username' => 'Username',
            'password' => 'Password',
        ];
    }

    public function formName(): string
    {
        return 'RegisterForm';
    }

    public function rules(): array
    {
        return [
            'username' => [new Required()],
            'password' => [new Required()],
        ];
    }
}
