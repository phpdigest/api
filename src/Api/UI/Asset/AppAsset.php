<?php

declare(strict_types=1);

namespace App\Api\UI\Asset;

use Yiisoft\Assets\AssetBundle;
use Yiisoft\Yii\Bootstrap5\Assets\BootstrapAsset;

class AppAsset extends AssetBundle
{
    public ?string $basePath = '@assets';
    public ?string $baseUrl = '@assetsUrl';
    public ?string $sourcePath = '@resources/assets/css';

    public array $css = [
        'site.css'
    ];

    public array $depends = [
        BootstrapAsset::class,
    ];
}
