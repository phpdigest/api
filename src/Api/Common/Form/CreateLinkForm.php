<?php

declare(strict_types=1);

namespace App\Api\Common\Form;

use App\Api\Common\Form\Validation\UrlRule;
use Yiisoft\Form\FormModel;
use Yiisoft\Validator\Rule\HasLength;
use Yiisoft\Validator\Rule\Required;

class CreateLinkForm extends FormModel
{
    protected ?string $url = null;
    protected ?string $description = null;

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function hasDescription(): bool
    {
        return $this->description !== null;
    }

    protected function rules(): array
    {
        return [
            'url' => [
                new Required(),
                new UrlRule(),
            ],
            'description' => [
                (new HasLength())->max(255)->skipOnEmpty(true)
            ],
        ];
    }
}
