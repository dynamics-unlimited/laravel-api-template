<?php
    namespace App\Providers;

    use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
    use Illuminate\Session\Middleware\StartSession;
    use Illuminate\Support\Facades\Route;

    class RouteServiceProvider extends ServiceProvider
    {
        /**
         * Define your route model bindings, pattern filters, and other route configuration.
         *
         * @return void
         */
        public function boot(): void
        {
            $this->routes(function () {
                Route::middleware('api')->prefix(env('API_URL_PREFIX'))
                     ->withoutMiddleware([StartSession::class])
                     ->namespace('App\Http\Controllers\Api')
                     ->group(base_path('routes/api.php'));
            });
        }
    }
