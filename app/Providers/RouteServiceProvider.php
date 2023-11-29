<?php

namespace App\Providers;

use App\Contracts\RouteRegistrar;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Contracts\Routing\Registrar;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use RuntimeException;
use Symfony\Component\HttpFoundation\Response;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/';

    protected array $registrars = [
        \App\Routing\OrderRegistrar::class,
        \App\Routing\CartRegistrar::class,
        \App\Routing\AppRegistrar::class,
        \App\Routing\AuthRegistrar::class,
        \App\Routing\CatalogRegistrar::class,
        \App\Routing\ProductRegistrar::class,
    ];

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('global', function (Request $request) {
            return Limit::perMinute(500)
                ->by($request->user()?->id ?: $request->ip())
                ->response(function (Request $request, array $headers) {
                    return response('Take it easy', Response::HTTP_TOO_MANY_REQUESTS, $headers);
                });
        });

        RateLimiter::for('auth', function (Request $request) {
            return Limit::perMinute(20)->by($request->ip());
        });

        $this->routes(function (Registrar $router) {
            $this->mapRoutes($router, $this->registrars);
//            Route::middleware('api')
//                ->prefix('api')
//                ->group(base_path('routes/api.php'));
//
//            Route::middleware('web')
//                ->group(base_path('routes/web.php'));
        });
    }

    protected function mapRoutes(Registrar $router, array $registrars): void
    {
        foreach ($registrars as $registrar) {
            if(!class_exists($registrar) || !is_subclass_of($registrar, RouteRegistrar::class)) {
                throw new RuntimeException(sprintf(
                    'Cannot map routes \'%s\', it is not a valid routes class',
                    $registrar
                ));
            }

            (new $registrar)->map($router);
        }
    }

}
