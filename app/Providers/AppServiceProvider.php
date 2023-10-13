<?php

namespace App\Providers;

use App\Http\Kernel;
use Carbon\CarbonInterval;
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
        // https://planetscale.com/blog/laravels-safety-mechanisms
        // N+1
        Model::preventLazyLoading(!app()->isProduction());
        // Model::fillable
        Model::preventSilentlyDiscardingAttributes(!app()->isProduction());

        // Оповещение если запрос дольше 500ms
        DB::whenQueryingForLongerThan(500, function (Connection $connection, QueryExecuted $event) {
            logger()->channel('telegram')->debug('DB::whenQueryingForLongerThan: ' . $connection->query()->toRawSql());
        });

        /** @var Kernel $kernel */
        $kernel = app(Kernel::class);
        $kernel->whenRequestLifecycleIsLongerThan(
            CarbonInterval::seconds(4),
            function () {
                logger()->channel('telegram')->debug('Kernel::whenRequestLifecycleIsLongerThan: ' . request()->url());
            }
        );
    }
}
