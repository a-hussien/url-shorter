<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Services\CacheOperations;
use App\Http\Requests\UrlShorterRequest;

class ShortUrlController extends Controller
{
    private $cacheOperations;

    public function __construct()
    {
        $this->cacheOperations = new CacheOperations('redis');
    }

    private function generateUniqueShortUrl(): string
    {
        do {
            $shortUrl = Str::random(6);
        } while ($this->cacheOperations->getCache($shortUrl) !== null);

        return $shortUrl;
    }

    public function generateShortUrl(UrlShorterRequest $request)
    {
        $request->validated();

        $generatedCode = $this->generateUniqueShortUrl();

        $this->cacheOperations->setCache($generatedCode, $request->url, $request->expires_time);

        $url = config('app.url') . '/url/' . $generatedCode;

        return response()->json([
            'status' => 'Success',
            'message' => 'Short URL generated successfully',
            'data' => [
                "key"   => $generatedCode,
                "url"   => $url,
                "time"  => $request->expires_time
            ]
        ]);
    }

    public function getShortUrl(string $key)
    {
        $url = $this->cacheOperations->getCache($key);

        abort_if(! $url, 404);

        return redirect($url);
    }

    public function removeShortUrl(Request $request)
    {
        $request->validate([
            'key' => 'required|string|min:6|max:6',
        ]);

        $result = $this->cacheOperations->removeCache($request->key);

        if(! $result) {
            return response()->json([
                'status' => 'Bad Request',
                'message' => 'Short URL not found',
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Short URL removed successfully',
        ]);
    }

    public function clearCachedUrls()
    {
        $this->cacheOperations->clearCache();

        return redirect('/');
    }
}
