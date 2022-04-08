<?php

namespace Tychovbh\LaravelCrud\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class MakeRoute extends Command
{
    public $signature = 'make:route {name : The name of the class} {--bindings}';

    public $description = 'Make routes';

    public function handle(): int
    {
        $name = $this->argument('name');
        $model = get_namespace(). 'Models\\' . Str::replace('Route', '', $name);
        $model = new $model();
        $file = sprintf('%s/app/Routes/%s.php', base_path(), $name);

        $plural = Str::lower($model->getTable());
        $singular = Str::singular($plural);

        file_replace('Route.php', [
            'Name' => $name,
            '{models}' => $plural,
            '{model}' => $this->option('bindings') ? $singular : 'id'
        ], $file, __DIR__);

        $this->comment('All done');

        return self::SUCCESS;
    }
}
