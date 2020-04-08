<?php


namespace TaylorNetwork\LaravelNexmo;

use Illuminate\Support\ServiceProvider;

class NexmoServiceProvider extends ServiceProvider
{
    /**
     * @inheritDoc
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/config/ncco.php', 'ncco');

        $this->publishes([ __DIR__ . '/resources/assets' => resource_path('assets/vendor/taylornetwork/laravel-nexmo')], 'assets');
        $this->publishes([ __DIR__ . '/config' => config_path()], 'config');
        $this->publishes([ __DIR__ . '/migrations' => database_path('migrations')], 'migrations');
    }

    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/migrations');
        $this->loadRoutesFrom(__DIR__.'/routes/api.php');

        app()->bind('NccoBuilder', NccoBuilder::class);
    }

}