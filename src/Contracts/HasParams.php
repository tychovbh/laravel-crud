<?php

namespace Tychovbh\LaravelCrud\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Trait HasParams
 * @package Tychovbh\LaravelCrud\Contracts
 * @property array $params
 */
trait HasParams
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
        $customParams = self::customParams($model, $query, $params);

        foreach ($params as $param => $value) {
            if (method_exists($customParams, $param)) {
                $customParams->{$param}($value);
                continue;
            }

            if (!in_array($param, $model->params)) {
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
