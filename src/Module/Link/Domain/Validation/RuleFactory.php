<?php

declare(strict_types=1);

namespace App\Module\Link\Domain\Validation;

use Yiisoft\Validator\Rule\HasLength;
use Yiisoft\Validator\Rule\Url;

final class RuleFactory
{
    private const URL_PATTERN = '/^({schemes}:\/\/)?(([A-Z0-9][A-Z0-9_-]*)(\.[A-Z0-9][A-Z0-9_-]*)+)(?::\d{1,5})?(?:$|[?\/#])/i';
    private const URL_LENGTH = 255;

    public static function createUrlRule(): Url
    {
        return (new Url())->pattern(self::URL_PATTERN)->enableIDN();
    }

    public static function createUrlLengthRule(): HasLength
    {
        return (new HasLength())->max(self::URL_LENGTH);
    }
}
