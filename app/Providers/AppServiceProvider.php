<?php

namespace App\Providers;

use App\Models\profile;
use Illuminate\Support\ServiceProvider;
use  Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
            View::composer('leyout.admin', function ($view) {
            if (Auth::check()) {
                $profile = Auth::user()->profile;
                $view->with('profile', $profile);
            } else {
                $view->with('profile', null);
            }
        });
        Schema::defaultStringLength(191);
    }
}
