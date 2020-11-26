<?php

declare(strict_types=1);

namespace App\Api\UI\Controller;

use Psr\Http\Message\ResponseInterface;
use Yiisoft\Aliases\Aliases;
use Yiisoft\View\ViewContextInterface;
use Yiisoft\View\WebView;
use Yiisoft\DataResponse\DataResponseFactoryInterface;
use Yiisoft\Yii\View\ViewRenderer;

use function array_merge;

abstract class AbstractController
{
    private ViewRenderer $webView;
    protected DataResponseFactoryInterface $responseFactory;

    public function __construct(
        DataResponseFactoryInterface $responseFactory,
        ViewRenderer $webView
    ) {
        $this->responseFactory = $responseFactory;
        $this->webView = $webView;
    }

    protected function render(string $view, array $parameters = []): ResponseInterface
    {
        return $this->webView->render($view, $parameters);
    }
}
