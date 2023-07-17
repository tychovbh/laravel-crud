<?php

namespace Tychovbh\LaravelCrud\Params;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

/**
 * Class Params
 * @property Builder $query
 * @package Tychovbh\LaravelCrud\Params
 */
abstract class Params
{
    /**
     * @var string
     */
    public string $table;

    /**
     * Params constructor.
     * @param string $table
     * @param Builder $query
     * @param array $params
     */
    public function __construct(string $table, Builder $query, array $params)
    {
        $this->table = $table;
        $this->query = $query;
    }
}
