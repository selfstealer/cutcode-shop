<?php

declare(strict_types=1);

namespace App\Routing;

use App\Contracts\RouteRegistrar;
use Illuminate\Contracts\Routing\Registrar;
use Illuminate\Support\Facades\Route;

class AuthRegistrar implements RouteRegistrar
{
    public function map(Registrar $registrar): void
    {
        Route::middleware('web')->group(function () {
            Route::controller(\App\Http\Controllers\Auth\SignInController::class)->group(static function () {
                Route::get('/login', 'page')->name('login');
                Route::post('/login', 'handle')->middleware('throttle:auth')->name('login.handle');
                Route::delete('/logout', 'logout')->name('logout');
            });
            Route::controller(\App\Http\Controllers\Auth\SignUpController::class)->group(static function () {
                Route::get('/sign-up', 'page')->name('sign-up');
                Route::post('/sign-up', 'handle')->middleware('throttle:auth')->name('sign-up.handle');
            });
            Route::controller(\App\Http\Controllers\Auth\ForgotPasswordController::class)->group(static function () {
                Route::get('/forgot-password', 'page')->name('password.request');
                Route::post('/forgot-password', 'handle')->middleware('guest')->name('password.email');
            });
            Route::controller(\App\Http\Controllers\Auth\ResetPasswordController::class)->group(static function () {
                Route::get('/reset-password/{token}', 'page')->middleware('guest')->name('password.reset');
                Route::post('/reset-password', 'handle')->middleware('guest')->name('password.update');
            });
            Route::controller(\App\Http\Controllers\Auth\SocialiteAuthController::class)->group(static function () {
                Route::get('/auth/socialite/{driver}', 'redirect')->name('socialite.redirect');
                Route::get('/auth/socialite/{driver}/callback', 'callback')->name('socialite.callback');
            });
        });
    }
}
