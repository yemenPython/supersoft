<?php

namespace App\Providers;

use App\Models\Branch;
use App\Observers\BranchObserver;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Schema::defaultStringLength(191);
        View::composer('*', 'App\Http\ViewComposers\PrintDataComposer');
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Branch::observe(BranchObserver::class);
    }
}
