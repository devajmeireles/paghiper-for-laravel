<?php

namespace DevAjMeireles\PagHiper;

use DevAjMeireles\PagHiper\Facades\PagHiper as PagHiperFacade;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class PagHiperServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function register(): void
    {
        $this->app->singleton(PagHiperFacade::class, fn (Application $app) => $app->make(PagHiper::class));
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/paghiper.php' => config_path('paghiper.php'),
        ], 'paghiper-config');
    }
}
