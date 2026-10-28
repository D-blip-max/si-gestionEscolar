<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;

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
        // Implicitly grant 'ADMINISTRADOR' role all permissions
        Gate::before(function ($user, $ability) {
            return $user->hasRole('ADMINISTRADOR') ? true : null;
        });

        // Forzar HTTPS en entorno de producción o cuando se defina en config
        if (config('app.env') === 'production' || config('app.force_https', false)) {
            URL::forceScheme('https');
        }
    }
}
