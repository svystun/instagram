<?php namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Jenssegers\Mongodb\Eloquent\Builder;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment('local', 'testing')) {
            $this->app->register(\Laravel\Dusk\DuskServiceProvider::class);
            $this->app->register(\Staudenmeir\DuskUpdater\DuskServiceProvider::class);
            Builder::macro('getName', function() {
                return $this->getModel()->getConnectionName();
            });
        }
    }
}
