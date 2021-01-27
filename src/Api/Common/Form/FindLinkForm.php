<?php

declare(strict_types=1);

namespace App\Api\Common\Form;

use App\Api\Common\Form\Validation\UrlRule;
use Yiisoft\Form\FormModel;
use Yiisoft\Validator\Rule\Required;

class FindLinkForm extends FormModel
{
    private ?string $url = null;

    public function getUrl(): string
    {
        return $this->url;
    }

    public function rules(): array
    {
        return [
            'url' => [
                new Required(),
                new UrlRule(),
            ],
        ];
    }
}
