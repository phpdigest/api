<?php

declare(strict_types=1);

namespace App\Api\UI\Form;

use App\Api\Common\Form\CreateLinkForm;

final class ShareLinkForm extends CreateLinkForm
{
    public function attributeLabels(): array
    {
        return [
            'url' => 'Url',
            'description' => 'Description',
        ];
    }

    public function formName(): string
    {
        return 'ShareLinkForm';
    }
}
