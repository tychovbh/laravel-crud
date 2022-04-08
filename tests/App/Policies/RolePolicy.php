<?php

namespace Tychovbh\LaravelCrud\Tests\App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Tychovbh\LaravelCrud\Tests\App\Models\Role;
use Tychovbh\LaravelCrud\Tests\App\Models\User;

class RolePolicy
{
    use HandlesAuthorization;


    /**
     * It can store.
     * @param User $authUser
     * @param Role $role
     * @return bool
     */
    public function view(User $authUser, Role $role): bool
    {
        return $authUser->id === $role->id;
    }
}
