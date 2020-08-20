<?php

declare(strict_types=1);

namespace App\Api\Telegram\ApiClient;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use Opis\Closure\SerializableClosure;
use Psr\Http\Client\ClientInterface;

class BackendApiClient
{
    protected ClientInterface $client;

    public function __construct(
        string $uri
    ) {
        $this->client = new Client([
            'base_uri' => $uri,
            'handler' => new SerializableClosure(fn() => (HandlerStack::create())(...func_get_args())),
        ]);
    }

    public function postLink(string $link, string $description): void
    {
        $this->client->post('/', [
            'form_params' => [
                'link' => $link,
                'description' => $description,
            ],
        ]);
    }
}
