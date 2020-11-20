<?php

declare(strict_types=1);

use Yiisoft\I18n\TranslatorInterface;
use Yiisoft\Translator\Translator;

/* @var array $params */

return [
    TranslatorInterface::class => [
        '__class' => Translator::class,
        '__construct()' => $params['yiisoft/i18n']['translator']
    ],
];
