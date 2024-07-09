<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShortUrlController;

Route::view('/', 'welcome');

Route::controller(ShortUrlController::class)->group(function () {
    Route::get('/url/{key}','getShortUrl');
    Route::get('/clear-urls', 'clearCachedUrls');
});
