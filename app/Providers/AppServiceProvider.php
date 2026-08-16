<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if (is_dir(base_path('../public_html'))) {
            $this->app->bind('path.public', function () {
                return base_path('../public_html');
            });
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom([
            database_path('migrations'),
            database_path('migrations/ami'),
        ]);

        Paginator::useBootstrap();
    }
}
