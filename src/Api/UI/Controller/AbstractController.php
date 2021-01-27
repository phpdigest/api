<?php

declare(strict_types=1);

namespace App\Api\UI\Controller;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Yiisoft\Yii\View\ViewRenderer;


abstract class AbstractController
{
    private ViewRenderer $webView;
    protected ResponseFactoryInterface $responseFactory;
    protected ?string $viewBasePath = null;
    protected ?string $layout = null;

    public function __construct(
        ResponseFactoryInterface $responseFactory,
        ViewRenderer $webView
    ) {
        $this->responseFactory = $responseFactory;
        $this->webView = $webView;
    }

    protected function render(string $view, array $parameters = []): ResponseInterface
    {
        $webView = $this->webView;
        if ($this->layout !== null) {
            $webView = $webView->withLayout($this->layout);
        }
        if ($this->viewBasePath !== null) {
            $webView = $webView->withViewBasePath($this->viewBasePath);
        }
        return $webView->render($view, $parameters);
    }
}
