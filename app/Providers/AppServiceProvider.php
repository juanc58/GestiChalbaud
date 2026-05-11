<?php

namespace App\Providers;

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
    public function boot(\Illuminate\Http\Request $request): void
    {
        // Forzamos HTTPS si la solicitud pasó por un túnel proxy
        if ($request->server('HTTP_X_FORWARDED_PROTO') === 'https' || $request->header('x-forwarded-proto') === 'https') {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }
    }
}
