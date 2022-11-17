<?php


namespace Tychovbh\LaravelCrud\Actions;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ModelName
{
    /**
     * The request name.
     * @param Request $request
     * @return string
     */
    public function get(Request $request): string
    {
        $name = $request->route()->getName();
        return Str::ucfirst(
            Str::camel(
                Str::singular(explode('.', $name)[0])
            )
        );
    }
}
