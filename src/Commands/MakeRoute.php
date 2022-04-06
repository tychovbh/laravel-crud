<?php

namespace Tychovbh\LaravelCrud\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class MakeRoute extends Command
{
    public $signature = 'make:route {name : The name of the class}';

    public $description = 'Make routes';

    public function handle(): int
    {
        $name = $this->argument('name');
        $model = get_namespace(). 'Models\\' . Str::replace('Route', '', $name);
        $model = new $model();
        $file = sprintf('%s/app/Routes/%s.php', base_path(), $name);

        file_replace('Route.php', [
            'Name' => $name,
            '{models}' => Str::lower($model->getTable())
        ], $file, __DIR__);

        $this->comment('All done');

        return self::SUCCESS;
    }
}
