<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class DomainServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->register(\Domain\Auth\Providers\AuthServiceProvider::class);
        $this->app->register(\Domain\Catalog\Providers\CatalogServiceProvider::class);
    }
}
