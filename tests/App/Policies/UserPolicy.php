<?php

namespace Tychovbh\LaravelCrud\Tests\App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Tychovbh\LaravelCrud\Tests\App\Models\User;

class UserPolicy
{
    use HandlesAuthorization;


    /**
     * It can store.
     * @param User $user
     * @return bool
     */
    public function store(User $user): bool
    {
        return true;
    }
}
