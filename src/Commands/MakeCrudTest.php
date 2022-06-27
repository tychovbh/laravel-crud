<?php

namespace Tychovbh\LaravelCrud\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class MakeCrudTest extends Command
{
    public $signature = 'make:crud-test {name : The name of the class} {--bindings}';

    public $description = 'Make a Crud Testcase';

    public function handle(): int
    {
        // TODO support bindings
        // TODO support auth:sanctum
        $name = $this->argument('name');
        $file = sprintf('%s/tests/Feature/%sTest.php', base_path(), $name);

        $singular = Str::lower($name);

        $template = $this->confirm('Support soft deletes?') ? 'TestSoftDeletes.php' : 'Test.php';
        $bindings = $this->option('bindings') ?? $this->confirm('Support bindings?');

        file_replace($template, [
            'Name' => $name,
            'singular' => $singular,
            'plural' => Str::plural($singular),
            '{model}' => $bindings ? $singular : 'id'
        ], $file, __DIR__);

        $this->comment('All done');

        return self::SUCCESS;
    }
}
