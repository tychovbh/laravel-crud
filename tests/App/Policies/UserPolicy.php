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
     * @param User $model
     * @return bool
     */
    public function view(User $authUser, User $model): bool
    {
        return $authUser->id === $model->id;
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
