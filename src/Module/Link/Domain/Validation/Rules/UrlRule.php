<?php

declare(strict_types = 1);

namespace App\Module\Link\Domain\Validation\Rules;

use Yiisoft\Validator\Rule\GroupRule;
use Yiisoft\Validator\Rule\HasLength;
use Yiisoft\Validator\Rule\Url;
use Yiisoft\Validator\Rules;

final class UrlRule extends GroupRule
{
    private const URL_PATTERN = '/^({schemes}:\/\/)?(([A-Z0-9][A-Z0-9_-]*)(\.[A-Z0-9][A-Z0-9_-]*)+)(?::\d{1,5})?(?:$|[?\/#])/i';
    private const URL_LENGTH  = 255;

    protected function getRules(): Rules
    {
        return new Rules(
            [
                (new Url())->pattern(self::URL_PATTERN)->enableIDN(),
                (new HasLength())->max(self::URL_LENGTH)

            ]
        );
    }
}
