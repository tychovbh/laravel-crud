<?php

namespace Tychovbh\LaravelCrud\Params;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

/**
 * Class Params
 * @property Builder query
 * @package Tychovbh\LaravelCrud\DefaultParams
 */
class DefaultParams
{
    /**
     * @var string
     */
    public string $table;

    /**
     * @var string
     */
    public string $between = 'created_at';

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

        if (Arr::has($params, 'between')) {
            $this->between = $params['between'];
        }
    }

    /**
     * Add param random
     */
    public function random()
    {
        $this->query->orderByRaw('RAND()');
    }

    /**
     * Add param sort
     * @param array|string $value
     */
    public function sort(array|string $value)
    {
        if (is_string($value)) {
            $sort = explode(' ', $value);
            $this->query->orderBy(...$sort);
        }

        if (is_array($value)) {
            foreach ($value as $order) {
                $sort = explode(' ', $order);
                $this->query->orderBy(...$sort);
            }
        }
    }

    /**
     * Filter users on from created_at
     * @param string $from
     */
    public function from(string $from)
    {
        $this->query->where($this->table . '.' . $this->between, '>=', $from);
    }

    /**
     * Filter users on till created_at
     * @param string $to
     */
    public function to(string $to)
    {
        $this->query->where($this->table . '.' . $this->between, '<=', $to);
    }

    /**
     * Filter only trashed.
     * @param bool $trashed
     */
    public function only_trashed(bool $trashed)
    {
        if ($trashed) {
            $this->query->onlyTrashed();
        }
    }

    /**
     * Filter with trashed (all).
     * @param bool $trashed
     */
    public function with_trashed(bool $trashed)
    {
        if ($trashed) {
            $this->query->withTrashed();
        }
    }
}
