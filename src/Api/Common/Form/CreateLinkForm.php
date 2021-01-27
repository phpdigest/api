<?php

declare(strict_types=1);

namespace App\Api\Common\Form;

use App\Api\Common\Form\Validation\UrlRule;
use Yiisoft\Form\FormModel;
use Yiisoft\Validator\Rule\HasLength;
use Yiisoft\Validator\Rule\Required;

class CreateLinkForm extends FormModel
{
    public const FIELD_URL = 'url';
    public const FIELD_DESCRIPTION = 'description';
    public const DESCRIPTION_MAX_LENGTH = 2048;

    protected ?string $url = null;
    protected ?string $description = null;
    protected ?string $source = null;

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

    public function withSource(?string $source): self
    {
        $new = clone $this;
        $new->source = $source;
        return $new;
    }

    public function getSource(): ?string
    {
        return $this->source;
    }

    public function rules(): array
    {
        return [
            'url' => [
                new Required(),
                new UrlRule(),
            ],
            'description' => [
                (new HasLength())->max(self::DESCRIPTION_MAX_LENGTH)->skipOnEmpty(true)
            ],
        ];
    }
}
