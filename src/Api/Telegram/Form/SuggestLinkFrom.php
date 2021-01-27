<?php

declare(strict_types=1);

namespace App\Api\Telegram\Form;

use App\Api\Common\Form\CreateLinkForm;

final class SuggestLinkFrom extends CreateLinkForm
{
    protected ?string $source = 'telegram';
}
