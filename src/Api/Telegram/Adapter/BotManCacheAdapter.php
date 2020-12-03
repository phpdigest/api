<?php

declare(strict_types=1);

namespace App\Api\Telegram\Adapter;

use BotMan\BotMan\Interfaces\CacheInterface;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface as Psr16CacheInterface;
use Yiisoft\Injector\Injector;

final class BotManCacheAdapter implements CacheInterface
{
    private const KEY_PREFIX = 'tg__';

    private Psr16CacheInterface $cache;
    private Injector $injector;
    private LoggerInterface $logger;

    public function __construct(Psr16CacheInterface $cache, Injector $injector, LoggerInterface $logger)
    {
        $this->cache = $cache;
        $this->injector = $injector;
        $this->logger = $logger;
    }

    public function has($key)
    {
        return $this->cache->has(self::KEY_PREFIX . $key);
    }

    public function get($key, $default = null)
    {
        $item = $this->cache->get(self::KEY_PREFIX . $key, $default);
        if (is_array($item) && array_key_exists('conversation', $item) && is_object($item['conversation'])) {
            try {
                $this->injector->invoke([$item['conversation'], '__construct']);
            } catch (\Exception $e) {
                $this->logger->error($e->getMessage(), ['exception' => $e]);
            }
        }
        return $item;
    }

    public function pull($key, $default = null)
    {
        try {
            return $this->get($key, $default);
        } finally {
            $this->cache->delete(self::KEY_PREFIX . $key);
        }
    }

    public function put($key, $value, $minutes)
    {
        $ttl = new \DateInterval(sprintf('PT%dM', $minutes));
        $this->cache->set(self::KEY_PREFIX . $key, $value, $ttl);
    }
}
