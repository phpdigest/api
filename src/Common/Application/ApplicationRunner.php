<?php

declare(strict_types=1);

namespace App\Common\Application;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\NullLogger;
use Throwable;
use Yiisoft\Composer\Config\Builder;
use Yiisoft\Di\Container;
use Yiisoft\ErrorHandler\ErrorHandler;
use Yiisoft\ErrorHandler\Middleware\ErrorCatcher;
use Yiisoft\ErrorHandler\Renderer\HtmlRenderer;
use Yiisoft\Files\FileHelper;
use Yiisoft\Http\Method;
use Yiisoft\Yii\Event\ListenerConfigurationChecker;
use Yiisoft\Yii\Web\Application;
use Yiisoft\Yii\Web\SapiEmitter;
use Yiisoft\Yii\Web\ServerRequestFactory;

use function microtime;

final class ApplicationRunner
{
    private bool $debug = false;
    private string $rootPath;

    public function __construct(string $rootPath)
    {
        $this->rootPath = $rootPath;
    }

    public function debug(bool $enable = true): void
    {
        $this->debug = $enable;
    }

    public function run(): void
    {
        $startTime = microtime(true);
        // Register temporary error handler to catch error while container is building.
        $errorHandler = new ErrorHandler(new NullLogger(), new HtmlRenderer());
        $this->registerErrorHandler($errorHandler);

        if ($this->debug && $this->shouldRebuildConfigs()) {
            Builder::rebuild();
        }

        $container = new Container(
            require Builder::path('web'),
            require Builder::path('providers-web')
        );

        // Register error handler with real container-configured dependencies.
        $this->registerErrorHandler($container->get(ErrorHandler::class), $errorHandler);

        $container = $container->get(ContainerInterface::class);

        if ($this->debug) {
            $container->get(ListenerConfigurationChecker::class)->check(require Builder::path('events-web'));
        }

        $application = $container->get(Application::class);

        $request = $container->get(ServerRequestFactory::class)->createFromGlobals();
        $request = $request->withAttribute('applicationStartTime', $startTime);

        try {
            $application->start();
            $response = $application->handle($request);
            $this->emit($request, $response);
        } catch (Throwable $throwable) {
            $handler = $this->createThrowableHandler($throwable);
            $response = $container->get(ErrorCatcher::class)->process($request, $handler);
            $this->emit($request, $response);
        } finally {
            $application->afterEmit($response ?? null);
            $application->shutdown();
        }
    }

    private function emit(RequestInterface $request, ResponseInterface $response): void
    {
        (new SapiEmitter())->emit($response, $request->getMethod() === Method::HEAD);
    }

    private function createThrowableHandler(Throwable $throwable): RequestHandlerInterface
    {
        return new class($throwable) implements RequestHandlerInterface {
            private Throwable $throwable;

            public function __construct(Throwable $throwable)
            {
                $this->throwable = $throwable;
            }

            public function handle(ServerRequestInterface $request): ResponseInterface
            {
                throw $this->throwable;
            }
        };
    }

    private function registerErrorHandler(ErrorHandler $registered, ErrorHandler $unregistered = null): void
    {
        if ($unregistered !== null) {
            $unregistered->unregister();
        }

        if ($this->debug) {
            $registered->debug();
        }

        $registered->register();
    }

    private function shouldRebuildConfigs(): bool
    {
        $sourceDirectory = $this->rootPath . '/config/';
        $buildDirectory = $this->rootPath . '/runtime/build/config/';

        if (FileHelper::isEmptyDirectory($buildDirectory)) {
            return true;
        }

        $sourceTime = FileHelper::lastModifiedTime($sourceDirectory);
        $buildTime = FileHelper::lastModifiedTime($buildDirectory);
        return $buildTime < $sourceTime;
    }
}
