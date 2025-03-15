<?php

namespace Innoboxrr\DomainManager\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{

    public function map()
    {

        $this->mapApiRoutes();      

    }

    protected function mapApiRoutes()
    {

        foreach (glob(__DIR__ . '/../../routes/api/models/*.php') as $file) {

            $name = basename($file, '.php');

            Route::middleware('api')
                ->prefix('api/innoboxrr/domainmanager/' . $name)
                ->as('api.innoboxrr.domainmanager.' . $name . '.')
                ->namespace('Innoboxrr\DomainManager\Http\Controllers')
                ->group($file);

        }

    }

}
