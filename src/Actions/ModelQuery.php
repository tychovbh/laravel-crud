<?php


namespace Tychovbh\LaravelCrud\Actions;


use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ModelQuery
{
    private string $model;

    public function __construct(string $model)
    {
        $this->model = $model;
    }

    public function start(Request $request): Builder
    {
        $model = $this->model;
        $query = $model::query();

        if ($request->method() !== 'GET') {
            return $query;
        }

        $params = $request->toArray();
        $defaults = $request->route()->defaults;
        $params = array_merge($params, $defaults);
        if ($params && method_exists($model, 'params')) {
            $query = $model::params($params);
        }

        $select = $request->get('select', ['*']);

        if (is_string($select)) {
            $select = explode(',', $select);
        }

        $select = array_map(fn($field) => $query->from . '.' . $field, $select);

        return $query->select($select);
    }
}
