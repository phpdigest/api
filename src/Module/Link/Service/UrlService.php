<?php

declare(strict_types=1);

namespace App\Module\Link\Service;

use App\Module\Link\Domain\Entity\Url;
use App\Module\Link\Domain\Repository\UrlRepository;
use Spiral\Database\Injection\Parameter;

final class UrlService
{
    private UrlRepository $repository;

    public function __construct(UrlRepository $repository) {
        $this->repository = $repository;
    }

    public function findUrl(string $url): ?Url
    {
        $chunks = $this->normalizeUrl($url);
        // https://www.php.net/manual/ru/function.http-build-query.php?utm_id=123&utm_term=asd&xren=aro&foo=bar
        return $this->repository->findOne(
            [
                'scheme' => in_array($chunks['scheme'], ['http', 'https'], true)
                    ? ['in' => new Parameter(['http', 'https'])]
                    : ['=' => $chunks['scheme']],
                'host' => ['=' => $chunks['host']],
                'path' => ['=' => $chunks['path']],
                'query' => ['=' => $chunks['query']],
            ]
        );
    }

    public function prepareUrl(string $url): Url
    {
        $chunks = $this->normalizeUrl($url);

        $url = new Url($chunks['host']);
        $url->query = $chunks['query'];
        $url->path = $chunks['path'];
        $url->scheme = $chunks['scheme'];

        return $url;
    }

    #[ArrayShape(["scheme" => "string", "host" => "string", "query" => "string", "path" => "string"])]
    #[Pure]
    private function normalizeUrl(string $url): array
    {
        $chunks = parse_url($url);
        if (!is_array($chunks) || !array_key_exists('host', $chunks)) {
            throw new \InvalidArgumentException(sprintf('$url should be valid url. "%s" is an invalid url.', $url));
        }

        return [
            'scheme' => strtolower($chunks['scheme'] ?? 'https'),
            'host' => strtolower($chunks['host']),
            'path' => $chunks['path'] ?? '',
            'query' => $this->normalizeUrlQuery($chunks['query'] ?? ''),
        ];
    }

    private function normalizeUrlQuery(string $queryStr): string
    {
        if ($queryStr === '') {
            return '';
        }
        $result = [];
        parse_str($queryStr, $output);
        foreach ($output as $key => $value) {
            // Clear UTM tags
            if (strcasecmp(substr($key, 0, 4), 'utm_') === 0) {
                continue;
            }
            $result[$key] = $value;
        }
        ksort($result);
        return http_build_query($result, '', '&', PHP_QUERY_RFC1738);
    }
}
