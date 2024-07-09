<?php

namespace App\Services\Inerfaces;

interface CacheOperationsInterface
{
    public function setCache($key, $value, $ttl): bool;

    public function getCache($key): mixed;

    public function removeCache($key): bool;

    public function clearCache(): bool;
}
