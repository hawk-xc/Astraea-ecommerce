<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
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
        $this->app->register(RepositoryServiceProvider::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrapFive();
        Paginator::useBootstrapFour();
        
        config(['app.locale' => 'id']);
        Carbon::setLocale('id');
        date_default_timezone_set('Asia/Jakarta');

        Gate::define('admin', function ($user) {
            return $user->role->name == 'admin';
        });

        Gate::define('operator', function ($user) {
            return $user->role->name == 'operator';
        });

        Gate::define('editor', function ($user) {
            return $user->role->name == 'editor';
        });

        Gate::define('all-roles', function ($user) {
            return $user->role->name == 'admin' || $user->role->name == 'operator' || $user->role->name == 'editor';
        });

    }
}
