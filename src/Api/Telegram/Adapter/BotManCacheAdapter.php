<?php

declare(strict_types=1);

namespace App\Api\Telegram\Adapter;

use BotMan\BotMan\Interfaces\CacheInterface;
use Psr\SimpleCache\CacheInterface as Psr16CacheInterface;

final class BotManCacheAdapter implements CacheInterface
{
    private Psr16CacheInterface $cache;

    public function __construct(Psr16CacheInterface $cache)
    {
        $this->cache = $cache;
    }

    public function has($key)
    {
        return $this->cache->has($key);
    }

    public function get($key, $default = null)
    {
        return $this->cache->get($key, $default);
    }

    public function pull($key, $default = null)
    {
        try {
            return $this->cache->get($key, $default);
        } finally {
            $this->cache->delete($key);
        }
    }

    public function put($key, $value, $minutes)
    {
        $ttl = new \DateInterval(sprintf('PT%dM', $minutes));
        $this->cache->set($key, $value, $ttl);
    }
}
