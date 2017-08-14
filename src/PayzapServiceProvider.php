<?php

namespace ConsoleTVs\Payzap;

use Illuminate\Support\ServiceProvider;

class PayzapServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/Views', 'payzap');

        $this->publishes([
            __DIR__.'/Config/payzap.php' => config_path('payzap.php'),
        ], 'payzap_config');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/Config/payzap.php', 'payzap');
    }
}
