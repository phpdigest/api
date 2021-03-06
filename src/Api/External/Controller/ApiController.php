<?php

declare(strict_types=1);

namespace App\Api\External\Controller;

use App\Api\External\Data\ApiBucket;
use App\Api\External\Data\ErrorBucket;
use App\Api\External\Exception\HttpException;
use App\Common\Domain\Exception\EntityNotFound;
use App\Module\User\Domain\Entity\Identity;
use ErrorException;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Throwable;
use Yiisoft\Auth\Middleware\Authentication;
use Yiisoft\Http\Header;
use Yiisoft\Http\Method;
use Yiisoft\Http\Status;
use Yiisoft\Injector\Injector;
use roxblnfk\SmartStream\Data\DataBucket;
use roxblnfk\SmartStream\SmartStreamFactory;
use Yiisoft\Validator\ValidatorInterface;

abstract class ApiController implements MiddlewareInterface
{
    protected ResponseFactoryInterface $responseFactory;
    protected ValidatorInterface $validator;
    private Injector $injector;

    public function __construct(
        ResponseFactoryInterface $responseFactory,
        Injector $injector,
        ValidatorInterface $validator
    ) {
        $this->responseFactory = $responseFactory;
        $this->injector = $injector;
        $this->validator = $validator;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            $callable = [$this, $request->getMethod()];
            if (!is_callable($callable)) {
                $data = (new ErrorBucket(new HttpException(Status::METHOD_NOT_ALLOWED, 'Method not allowed.'), false))
                    ->withHeader(Header::ALLOW, implode(', ', $this->getAllowedMethods()));
            } else {
                $data = $this->injector->invoke($callable, [$request]);
            }
        } catch (Throwable $e) {
            $data = $this->errorToBucket($e);
        }
        return $this->prepareResponse($data, $request);
    }

    protected function errorToBucket(Throwable $error): DataBucket
    {
        $bucket =  new ErrorBucket($error);

        if ($error instanceof EntityNotFound) {
            $bucket = $bucket->withStatusCode(Status::NOT_FOUND);
        } elseif ($error instanceof ErrorException) {
            $bucket = $bucket->withStatusCode(Status::INTERNAL_SERVER_ERROR);
        }

        return $bucket;
    }

    /**
     * @param mixed $data
     */
    protected function prepareResponse($data, ?ServerRequestInterface $request = null): ResponseInterface
    {
        if ($data instanceof ResponseInterface) {
            return $data;
        }
        if ($data instanceof StreamInterface) {
            $stream = $data;
        } else {
            $smartStreamFactory = $this->injector
                ->make(SmartStreamFactory::class)
                ->withDefaultBucketClass(ApiBucket::class);
            $stream = $smartStreamFactory->createStream($data, $request);
        }
        return $this->responseFactory->createResponse()->withBody($stream);
    }

    protected function getAllowedMethods(): array
    {
        $result = [];
        foreach (Method::ANY as $method) {
            if (is_callable([$this, $method])) {
                $result[] = $method;
            }
        }
        return $result;
    }

    protected function getIdentityFromRequest(ServerRequestInterface $request): Identity
    {
        $identity = $request->getAttribute(Authentication::class);
        if (!$identity instanceof Identity) {
            throw new HttpException(Status::NOT_FOUND, 'User not found.');
        }
        return $identity;
    }
}
