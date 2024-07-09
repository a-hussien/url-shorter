<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShortUrlController;

Route::controller(ShortUrlController::class)->group(function () {
    Route::post('/generate-url', 'generateShortUrl');
    Route::delete('/remove-url', 'removeShortUrl');
});
