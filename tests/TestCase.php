<?php

namespace Tests;

use DevAjMeireles\PagHiper\PagHiperServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use NunoMaduro\Collision\Provider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        (new Provider)->register();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'DevAjMeireles\\PagHiper\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app): array
    {
        return [
            PagHiperServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app): void
    {
        $migration = include __DIR__.'/../database/migrations/create_billet_table.php.stub';
        $migration->up();
    }
}
