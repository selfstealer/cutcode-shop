<?php

declare(strict_types=1);

namespace Domain\Product\Providers;

use Illuminate\Support\ServiceProvider;

class ProductServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(
            ActionsServiceProvider::class
        );
    }

    public function boot(): void
    {
        //
    }
}
