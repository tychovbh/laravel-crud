<?php

namespace Tychovbh\LaravelCrud\Tests\App\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tychovbh\LaravelCrud\Tests\Database\Factories\UserFactory;
use Tychovbh\LaravelCrud\Contracts\GetParams;

class User extends Authenticatable
{
    use HasFactory, GetParams;

    /**
     * @var array
     */
    protected $fillable = ['name', 'email', 'verified'];

    /**
     * @var array
     */
    protected array $params = ['name', 'email', 'verified'];

    /**
     * Create a new factory instance for the model.
     *
     * @return Factory<static>
     */
    protected static function newFactory()
    {
        return new UserFactory();
    }
}
