<?php

namespace DevAjMeireles\PagHiper\Console;

use Illuminate\Console\Command;

class InstallPagHiperCommand extends Command
{
    protected $signature = 'paghiper:install';

    protected $description = 'Install and preparing the PagHiper for Laravel package';

    public function handle(): int
    {
        $this->call('vendor:publish', [
            '--provider' => 'DevAjMeireles\PagHiper\PagHiperServiceProvider',
            '--tag'      => 'paghiper-config',
        ]);

        $this->components->info('PagHiper for Laravel Installed Successfully.');

        if (!file_exists($env = base_path('.env'))) {
            return self::SUCCESS;
        }

        if (str($original = file_get_contents($env))->contains('PAGHIPER_TOKEN')) {
            return self::SUCCESS;
        }

        $original .= PHP_EOL;
        $original .= 'PAGHIPER_API=' . PHP_EOL;
        $original .= 'PAGHIPER_TOKEN=' . PHP_EOL;
        $original .= 'PAGHIPER_EMAIL=' . PHP_EOL;

        file_put_contents($env, $original);

        return self::SUCCESS;
    }
}
