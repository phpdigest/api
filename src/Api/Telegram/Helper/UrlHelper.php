<?php

declare(strict_types=1);

namespace App\Api\Telegram\Helper;

use App\Api\Common\Form\Validation\UrlRule;

final class UrlHelper
{
    public function validate(string $url): bool
    {
        if (strpos($url, '.') === false) {
            return false;
        }

        $url = $this->normalize($url);

        return (new UrlRule())->validate($url)->isValid();
    }

    public function normalize(string $url): string
    {
        if (stripos($url, 'http://') === false && stripos($url, 'https://') === false) {
            $url = 'https://' . $url;
        }

        return $url;
    }
}
