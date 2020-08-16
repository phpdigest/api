<?php

declare(strict_types=1);

namespace App\Common\Application;

use App\Api\External\Data\ErrorBucket;
use App\Api\External\Exception\HttpException;
use Exception;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Server\RequestHandlerInterface;
use roxblnfk\SmartStream\Data\DataBucket;
use roxblnfk\SmartStream\SmartStreamFactory;
use Yiisoft\Http\Header;
use Yiisoft\Http\Status;

final class AuthRequestErrorHandler implements RequestHandlerInterface
{
    private SmartStreamFactory $smartStreamFactory;
    private ResponseFactoryInterface $responseFactory;

    public function __construct(SmartStreamFactory $smartStreamFactory, ResponseFactoryInterface $responseFactory)
    {
        $this->smartStreamFactory = $smartStreamFactory;
        $this->responseFactory = $responseFactory;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->createResponse($this->createBody());
    }

    private function createBody(): StreamInterface
    {
        return $this->smartStreamFactory->createStream($this->createErrorBucket());
    }

    private function createResponse(StreamInterface $stream): ResponseInterface
    {
        return $this->responseFactory
            ->createResponse(Status::UNAUTHORIZED)
            ->withBody($stream)
            ->withHeader(Header::CONTENT_TYPE, 'application/json');
    }

    private function createErrorBucket(): DataBucket
    {
        return new ErrorBucket($this->createError());
    }

    private function createError(): Exception
    {
        return new HttpException(Status::UNAUTHORIZED, 'Your request was made with invalid credentials.');
    }
}
