<?php

declare(strict_types=1);

namespace App\Routing;

use App\Contracts\RouteRegistrar;
use Illuminate\Contracts\Routing\Registrar;
use Illuminate\Support\Facades\Route;

class AppRegistrar implements RouteRegistrar
{
    public function map(Registrar $registrar): void
    {
        Route::middleware('web')->group(function () {

            Route::get('/', \App\Http\Controllers\HomeController::class)->name('home');

            Route::get('/telegram', function () {
                logger()->channel('telegram')->debug('test');
            })->name('telegram');

            Route::get('/storage/images/{dir}/{method}/{size}/{file}', \App\Http\Controllers\ThumbnailController::class)
                ->where('method', 'resize|crop|fit')
                ->where('size', '\d+x\d+')
                ->where('file', '.*\.(png|jpg|jpeg|gif)$')
                ->name('thumbnail');

        });
    }
}
