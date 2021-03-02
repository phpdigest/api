<?php

declare(strict_types=1);

namespace App\Api\UI\Widget;

use Yiisoft\Html\Html;

class SourceIcon
{
    private const ICONS = [
        'web-form' => 'fas fa-globe',
        'telegram' => 'fab fa-telegram',
        'api' => 'fas fa-share-alt-square',
    ];

    public static function render(?string $source, array $options = []): string
    {
        if ($source === null) {
            return '';
        }
        Html::addCssClass($options, self::ICONS[$source] ?? 'fas fa-question');
        return Html::tag('i', '', $options)->render();
    }
}
