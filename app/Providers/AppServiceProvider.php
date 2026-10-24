<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
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
        // Ngăn chặn N+1 query ở môi trường local/testing
        Model::preventLazyLoading(! $this->app->isProduction());

        // Sử dụng giao diện phân trang Bootstrap 5
        Paginator::useBootstrapFive();
    }
}
