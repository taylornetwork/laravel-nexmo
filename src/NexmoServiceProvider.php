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

    }

    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/migrations');
    }

}