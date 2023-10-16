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
        // Сахар для 3 методов сразу
        Model::shouldBeStrict(!app()->isProduction());

        if (!app()->isProduction()) {
            return;
        }

        // Оповещение если запрос дольше 500ms
        // нет это если весь connection дольше 5000mc
        DB::whenQueryingForLongerThan(CarbonInterval::seconds(5), static function (Connection $connection) {
            logger()->channel('telegram')->debug('DB::whenQueryingForLongerThan: ' . $connection->totalQueryDuration());
        });

        // а вот теперь смотрим каждый запрос
        DB::listen(static function (QueryExecuted $event) {
            if ($event->time > 500) {
                logger()->channel('telegram')->debug('DB::listen: ' . $event->sql);
            }
        });

        /** @var Kernel $kernel */
        $kernel = app(Kernel::class);
        $kernel->whenRequestLifecycleIsLongerThan(
            CarbonInterval::seconds(4),
            static function () {
                logger()->channel('telegram')->debug('Kernel::whenRequestLifecycleIsLongerThan: ' . request()->url());
            }
        );
    }
}
