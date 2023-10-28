<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

//Route::controller(\App\Http\Controllers\Auth\SignInController::class)->group(static function () {
//    Route::get('/login', 'index')->name('login');
//    Route::post('/sign-in', 'signIn')->middleware('throttle:auth')->name('sign-in');
//
//    Route::get('/sign-up', 'signUp')->name('sign-up');
//    Route::post('/sign-up', 'store')->middleware('throttle:auth')->name('store');
//
//    Route::get('/forgot-password', 'forgotPassword')->name('password.request');
//    Route::post('/forgot-password', 'forgot')->middleware('guest')->name('password.email');
//
//    Route::get('/reset-password/{token}', 'resetPassword')->middleware('guest')->name('password.reset');
//    Route::post('/reset-password', 'reset')->middleware('guest')->name('password.update');
//
//    Route::delete('/logout', 'logout')->name('logout');
//
//    Route::get('/auth/socialite/github', 'github')->name('socialite.github');
//
//    Route::get('/auth/socialite/github/callback', 'githubCallback')->name('socialite.github.callback');
//});
//
//Route::get('/', App\Http\Controllers\HomeController::class)->name('home');
//
//Route::get('/telegram', function () {
//    logger()->channel('telegram')->debug('test');
//})->name('telegram');
