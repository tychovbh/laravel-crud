<?php

namespace Tychovbh\LaravelCrud\Tests\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tychovbh\LaravelCrud\Tests\Database\Factories\UserFactory;
use Tychovbh\LaravelCrud\Contracts\HasParams;

class User extends Authenticatable
{
    use HasFactory, HasParams;

    /**
     * @var array
     */
    protected $fillable = ['name', 'email'];

    /**
     * @var array
     */
    protected array $params = ['name', 'email'];

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory<static>
     */
    protected static function newFactory()
    {
        return new UserFactory();
    }
}
