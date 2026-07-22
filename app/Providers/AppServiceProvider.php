<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
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
        $host = $_SERVER['HTTP_HOST'] ?? '';
        if ($host && !in_array($host, ['localhost', '127.0.0.1', '127.0.0.1:8000', 'localhost:8000'])) {
            URL::forceScheme('https');
            if ($this->app->has('request')) {
                $this->app['request']->server->set('HTTPS', 'on');
            }
        } elseif (config('app.env') === 'production' || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')) {
            URL::forceScheme('https');
            if ($this->app->has('request')) {
                $this->app['request']->server->set('HTTPS', 'on');
            }
        }
    }
}
