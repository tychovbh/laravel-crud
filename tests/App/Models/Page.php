<?php

namespace Tychovbh\LaravelCrud\Tests\App\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Tychovbh\LaravelCrud\Tests\Database\Factories\PageFactory;

class Page extends Model
{
    use HasFactory;

    /**
     * @var array
     */
    protected $fillable = ['title', 'url'];

    /**
     * Create a new factory instance for the model.
     *
     * @return Factory<static>
     */
    protected static function newFactory()
    {
        return new PageFactory();
    }
}
