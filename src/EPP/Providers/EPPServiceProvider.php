<?php

namespace DreamHun\EPP\Providers;

use DreamHun\EPP\Client;
use Illuminate\Support\ServiceProvider;

class EPPServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register the config file
        $this->mergeConfigFrom(
            __DIR__ . '/../../../config/epp.php', 'epp'
        );

        // Register the EPP client as a singleton
        $this->app->singleton(Client::class, function ($app) {
            return new Client();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Publish the config file
        $this->publishes([
            __DIR__ . '/../../../config/epp.php' => config_path('epp.php'),
        ], 'epp-config');
    }
}
