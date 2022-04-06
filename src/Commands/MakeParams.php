<?php

namespace Tychovbh\LaravelCrud\Commands;

use Illuminate\Console\Command;

class MakeParams extends Command
{
    public $signature = 'make:params {name : The name of the class}';

    public $description = 'Make a Custom Params filter';

    public function handle(): int
    {
        $name = $this->argument('name');
        $file = sprintf('%s/app/Params/%s.php', base_path(), $name);

        file_replace('Params.php', [
            'Name' => $name,
        ], $file, __DIR__);

        $this->comment('All done');

        return self::SUCCESS;
    }
}
