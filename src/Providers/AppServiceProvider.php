<?php

namespace Innoboxrr\DomainManager\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    public function register()
    {
        
        $this->mergeConfigFrom(__DIR__ . '/../../config/domain-manager.php', 'domain-manager');

    }

    public function boot()
    {
        
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

        // $this->loadViewsFrom(__DIR__.'/../../resources/views', 'domain-manager');

        if ($this->app->runningInConsole()) {
            
            // $this->publishes([__DIR__.'/../../resources/views' => resource_path('views/vendor/domain-manager'),], 'views');

            $this->publishes([__DIR__.'/../../config/domain-manager.php' => config_path('domain-manager.php')], 'config');

        }

    }
    
}