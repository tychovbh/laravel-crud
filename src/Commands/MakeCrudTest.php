<?php

namespace Tychovbh\LaravelCrud\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class MakeCrudTest extends Command
{
    public $signature = 'make:crud-test {name : The name of the class}';

    public $description = 'Make a Crud Testcase';

    public function handle(): int
    {
        $name = $this->argument('name');
        $file = sprintf('%s/tests/Feature/%sTest.php', base_path(), $name);

        $singular = Str::lower($name);

        $template = $this->confirm('Support soft deletes?') ? 'TestSoftDeletes.php' : 'Test.php';

        file_replace($template, [
            'Name' => $name,
            'singular' => Str::lower($name),
            'plural' => Str::plural($singular),
        ], $file, __DIR__);

        $this->comment('All done');

        return self::SUCCESS;
    }
}
