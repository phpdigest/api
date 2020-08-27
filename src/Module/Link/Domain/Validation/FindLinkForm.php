<?php

declare(strict_types=1);

namespace App\Module\Link\Domain\Validation;

use App\Module\Link\Domain\Validation\Rules\UrlRule;
use Yiisoft\Form\FormModel;
use Yiisoft\Validator\Rule\Required;

final class FindLinkForm extends FormModel
{
    private ?string $url = null;

    public function getUrl(): string
    {
        return $this->url;
    }

    protected function rules(): array
    {
        return [
            'url' => [
                new Required(),
                new UrlRule()
            ],
        ];
    }
}
