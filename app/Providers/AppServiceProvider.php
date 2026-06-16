<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator; // 1. Pastikan ini ada

use Illuminate\Support\Facades\View;
use App\Models\Sekolah;

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
        Paginator::defaultView('vendor.pagination.custom');
        Paginator::defaultSimpleView('vendor.pagination.simple-custom');

        // Share data sekolah ke semua view
        View::composer('*', function ($view) {
            $view->with('sekolah', Sekolah::first());
        });
    }
}