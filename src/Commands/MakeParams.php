<?php

namespace Tychovbh\LaravelCrud\Commands;

use Illuminate\Console\Command;

class MakeParams extends Command
{
    public $signature = 'make:params {name : The params name (singular)}';

    public $description = 'Make a Custom Params filter';

    public function handle(): int
    {
        $name = $this->argument('name');
        $filename = $name . 'Params';
        $file = sprintf('%s/app/Params/%s.php', base_path(), $filename);

        file_replace('Params.php', [
            'Model' => $name,
        ], $file, __DIR__);

        $this->comment('All done');

        return self::SUCCESS;
    }
}
