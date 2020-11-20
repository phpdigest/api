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

abstract class AbstractController implements ViewContextInterface
{
    private ViewRenderer $webView;
    protected Aliases $aliases;
    protected DataResponseFactoryInterface $responseFactory;

    public function __construct(
        Aliases $aliases,
        DataResponseFactoryInterface $responseFactory,
        ViewRenderer $webView
    ) {
        $this->aliases = $aliases;
        $this->responseFactory = $responseFactory;
        $this->webView = $webView;
    }

    protected function render(string $view, array $parameters = []): ResponseInterface
    {
        return $this->webView->withCsrf()
                             ->render($view, $parameters);
    }

    abstract public function getViewPath(): string;
}
