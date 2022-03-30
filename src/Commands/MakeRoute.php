<?php

namespace Tychovbh\LaravelCrud\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class MakeRoute extends Command
{
    public $signature = 'make:route {name : The route name (singular)}';

    public $description = 'Make routes';

    public function handle(): int
    {
        $name = $this->argument('name');
        $filename = $name . 'Route';
        $file = sprintf('%s/app/Routes/%s.php', base_path(), $filename);

        file_replace('Route.php', [
            'Model' => $name,
            '{models}' => Str::lower(Str::plural($name))
        ], $file, __DIR__);

        $this->comment('All done');

        return self::SUCCESS;
    }
}
