<?php

namespace App\Providers;

use Illuminate\Database\Connection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // N+1
        Model::preventLazyLoading(!app()->isProduction());
        // Model::fillable
        Model::preventSilentlyDiscardingAttributes(!app()->isProduction());

        // Оповещение если запрос дольше 500ms
        DB::whenQueryingForLongerThan(500, function (Connection $connection, QueryExecuted $event) {
            // TODO 3rd lesson
        });

        // TODO 3rd lesson request cycle
    }
}
