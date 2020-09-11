<?php

declare(strict_types=1);

namespace App\Api\Telegram\Controller;

use BotMan\BotMan\BotMan;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Yiisoft\Http\Status;

class WebhookAction
{
    private ResponseFactoryInterface $responseFactory;

    private BotMan $botMan;

    private StreamFactoryInterface $streamFactory;

    public function __construct(
        StreamFactoryInterface $streamFactory,
        ResponseFactoryInterface $responseFactory,
        BotMan $botMan
    ) {
        $this->responseFactory = $responseFactory;
        $this->botMan = $botMan;
        $this->streamFactory = $streamFactory;
    }

    public function __invoke()
    {
        $this->botMan->listen();

        return $this->responseFactory
            ->createResponse(Status::OK, Status::TEXTS[Status::OK])
            ->withBody($this->streamFactory->createStream('ok'))
            ;
    }
}
