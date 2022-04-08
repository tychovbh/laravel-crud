<?php

namespace Tychovbh\LaravelCrud\Tests\App\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Tychovbh\LaravelCrud\Tests\Database\Factories\RoleFactory;

class Role extends Model
{
    use HasFactory;

    /**
     * @var array
     */
    protected $fillable = ['name', 'label'];

    /**
     * Create a new factory instance for the model.
     *
     * @return Factory<static>
     */
    protected static function newFactory()
    {
        return new RoleFactory();
    }
}
