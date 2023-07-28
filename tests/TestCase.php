<?php

namespace Tests;

use DevAjMeireles\PagHiper\PagHiperServiceProvider;
use NunoMaduro\Collision\Provider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        (new Provider())->register();
    }

    protected function getPackageProviders($app): array
    {
        return [
            PagHiperServiceProvider::class,
        ];
    }
}
