<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use App\Services\Inerfaces\CacheOperationsInterface;

class CacheOperations implements CacheOperationsInterface
{
    public function __construct(public string $store = 'file') {}

    public function setCache($key, $value, $ttl = 3600): bool
    {
        return Cache::store($this->store)->put($key, $value, $ttl);
    }

    public function getCache($key): mixed
    {
        if (! Cache::store($this->store)->has($key)) {
            return null;
        }

        return Cache::store($this->store)->get($key);
    }

    public function removeCache($key): bool
    {
        return Cache::store($this->store)->forget($key);
    }

    public function clearCache(): bool
    {
        return Cache::store($this->store)->flush();
    }
}
