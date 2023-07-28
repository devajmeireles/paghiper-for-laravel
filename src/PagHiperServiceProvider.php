<?php

namespace DevAjMeireles\PagHiper;

use Illuminate\Support\ServiceProvider;

class PagHiperServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/paghiper.php' => config_path('paghiper.php'),
        ], 'paghiper-config');
    }
}
