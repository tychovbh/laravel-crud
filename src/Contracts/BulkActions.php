<?php

namespace Tychovbh\LaravelCrud\Contracts;

use Illuminate\Database\Eloquent\Model;

trait BulkActions
{
    /**
     * Map through array and restore records
     * @param array $ids
     * @return bool
     */
    public static function bulkRestore(array $ids): bool
    {
        foreach ($ids as $id) {
            /** @var Model $class */
            $class = self::class;
            $class::onlyTrashed()->find($id)->restore();
        }

        return true;
    }

    /**
     * Map through array and softDelete records
     * @param array $ids
     * @return bool|void
     */
    public static function bulkDestroy(array $ids)
    {
        foreach ($ids as $id) {
            /** @var Model $class */
            $class = self::class;
            $class::find($id)->delete();
        }

        return true;
    }


    /**
     * Map through array and delete records out of the DB
     * @param array $ids
     * @return bool
     */
    public static function bulkForceDestroy(array $ids): bool
    {
        foreach ($ids as $id) {
            /** @var Model $class */
            $class = self::class;
            $model = $class::withTrashed()->where('id', $id);
            $model->forceDelete();
        }

        return true;
    }
}
