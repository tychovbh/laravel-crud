<?php

namespace Tychovbh\LaravelCrud\Tests\App\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tychovbh\LaravelCrud\Contracts\BulkActions;
use Tychovbh\LaravelCrud\Contracts\GetParams;
use Tychovbh\LaravelCrud\Tests\Database\Factories\PostFactory;

class Post extends Model
{
    use HasFactory, SoftDeletes, GetParams, BulkActions;

    /**
     * @var array
     */
    protected $fillable = ['title', 'description'];

    /**
     * Create a new factory instance for the model.
     *
     * @return Factory<static>
     */
    protected static function newFactory()
    {
        return new PostFactory();
    }
}
