<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
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

        // Aplicar configuración dinámica de SMTP desde base de datos (Mailpit por defecto o proveedor externo)
        \App\Http\Controllers\SmtpSettingController::applyDynamicMailConfig();

        // Compartir contador de solicitudes de usuarios pendientes en el layout principal
        View::composer('dashboard', function ($view) {
            if (Auth::check() && Auth::user()->isAdmin()) {
                $view->with('pendingUsersCount', User::whereNull('role_id')->count());
            }
        });
    }
}
