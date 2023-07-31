<?php

namespace DevAjMeireles\PagHiper;

use DevAjMeireles\PagHiper\Console\InstallPagHiperCommand;
use DevAjMeireles\PagHiper\Facades\PagHiper as PagHiperFacade;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class PagHiperServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(PagHiperFacade::class, fn (Application $app) => $app->make(PagHiper::class));

        $this->mergeConfigFrom(__DIR__ . '/../config/paghiper.php', 'paghiper');
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/paghiper.php' => config_path('paghiper.php'),
        ], 'paghiper');

        if ($this->app->runningInConsole()) {
            $this->commands([InstallPagHiperCommand::class]);
        }
    }
}
