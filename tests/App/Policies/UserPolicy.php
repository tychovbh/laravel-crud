<?php

namespace Tychovbh\LaravelCrud\Tests\App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Tychovbh\LaravelCrud\Tests\App\Models\User;

class UserPolicy
{
    use HandlesAuthorization;


    /**
     * It can store.
     * @param User $authUser
     * @param User $user
     * @return bool
     */
    public function view(User $authUser, User $user): bool
    {
        return $authUser->id === $user->id;
    }

    /**
     * It can store.
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return true;
    }
}
