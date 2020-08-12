<?php

declare(strict_types=1);

namespace App\Api\External\Controller;

use App\Api\External\Data\ErrorBucket;
use App\Api\External\Exception\HttpException;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use roxblnfk\SmartStream\Data\DataBucket;
use roxblnfk\SmartStream\SmartStreamFactory;
use RuntimeException;
use Throwable;
use Yiisoft\Http\Status;
use Yiisoft\Injector\Injector;

abstract class ApiController implements MiddlewareInterface
{
    protected ResponseFactoryInterface $responseFactory;
    protected SmartStreamFactory $smartStreamFactory;
    private Injector $injector;

    public function __construct(
        ResponseFactoryInterface $responseFactory,
        SmartStreamFactory $smartStreamFactory,
        Injector $injector
    ) {
        $this->responseFactory = $responseFactory;
        $this->smartStreamFactory = $smartStreamFactory;
        $this->injector = $injector;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            $callable = [$this, $request->getMethod()];
            if (!is_callable($callable)) {
                throw new HttpException(Status::METHOD_NOT_ALLOWED, 'Method not allowed.');
            }
            $data = $this->injector->invoke($callable, [$request]);
        } catch (Throwable $e) {
            $data = $this->errorToBucket($e);
        }
        return $this->prepareResponse($data, $request);
    }

    protected function errorToBucket(Throwable $error): DataBucket {
        $bucket = new ErrorBucket($error, false);
        if ($error instanceof HttpException) {
            $bucket = $bucket->withStatusCode($error->getStatus());
        }
        return $bucket;
    }

    protected function prepareResponse($data, ?ServerRequestInterface $request = null): ResponseInterface
    {
        if ($data instanceof ResponseInterface) {
            return $data;
        }
        if ($data instanceof StreamInterface) {
            $stream = $data;
        } else {
            $stream = $this->smartStreamFactory->createStream($data, $request);
        }
        return $this->responseFactory->createResponse()->withBody($stream);
    }
}
