<?php

namespace DevAjMeireles\PagHiper\Console;

use Illuminate\Console\Command;

class InstallPagHiperCommand extends Command
{
    protected $signature = 'paghiper:install {--force : Overwrite any existing files}';

    protected $description = 'Install and preparing the PagHiper for Laravel package';

    public function handle(): int
    {
        $this->callSilent('vendor:publish', [
            '--tag'   => 'paghiper',
            '--force' => $this->option('force'),
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

        file_put_contents($env, $original);

        return self::SUCCESS;
    }
}
