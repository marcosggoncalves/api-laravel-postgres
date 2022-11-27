<?php

namespace App\Providers;

use App\Interfaces\IClientesInterface;
use App\Interfaces\IGerentesInterface;
use App\Interfaces\IGruposInterface;
use App\Repositories\ClientesRepository;
use App\Repositories\GerentesRepository;
use App\Repositories\GruposRepository;
use Illuminate\Support\ServiceProvider;

class RepositoriesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(IGerentesInterface::class, GerentesRepository::class);
        $this->app->bind(IClientesInterface::class, ClientesRepository::class);
        $this->app->bind(IGruposInterface::class, GruposRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
