<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Interfaces\TipoFirmaRepository;
use App\Interfaces\TipoFirmaRepositoryInterface;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(TipoFirmaRepositoryInterface::class, TipoFirmaRepository::class);

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