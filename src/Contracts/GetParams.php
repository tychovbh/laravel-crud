<?php

namespace Tychovbh\LaravelCrud\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Tychovbh\LaravelCrud\Params\DefaultParams;

/**
 * Trait HasParams
 * @package Tychovbh\LaravelCrud\Contracts
 * @property array $params
 */
trait GetParams
{
    /**
     * Apply params.
     * @param array $params
     * @return Builder
     */
    public static function params(array $params): Builder
    {
        $model = new self();
        $query = self::query();
        $defaultParams = new DefaultParams($model->getTable(), $query, $params);
        $customParams = self::customParams($model, $query, $params);

        foreach ($params as $param => $value) {
            if ($customParams && method_exists($customParams, $param)) {
                $customParams->{$param}($value);
                continue;
            }

            if (method_exists($defaultParams, $param)) {
                $defaultParams->{$param}($value);
                continue;
            }

            if (!property_exists($model, 'params') || !in_array($param, $model->params)) {
                continue;
            }

            $key = $model->getTable() . '.' . $param;

            if ($value === null || $value === 'null') {
                $query->whereNull($key);
                continue;
            }

            is_array($value) ? $query->whereIn($key, $value) : $query->where($key, $value);
        }

        return $query;
    }

    /**
     * @param Model $model
     * @param Builder $query
     * @param array $params
     * @return mixed
     */
    private static function customParams(Model $model, Builder $query, array $params): mixed
    {
        $class = Str::replace('Models', 'Params', self::class) . 'Params';

        if (class_exists($class)) {
            return new $class($model->getTable(), $query, $params);
        }

        return null;
    }
}
