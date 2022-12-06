<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use Spatie\Permission\Models\Role;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     *
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->can('viewAny users');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     *
     * @return bool
     */
    public function view(User $user): bool
    {
        return $user->can('view users');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     *
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->can('update users');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param User $model
     *
     * @return bool
     */
    public function update(User $user, User $model): bool
    {
        return $user->can('update users');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param User $model
     *
     * @return bool
     */
    public function delete(User $user, User $model): bool
    {
        if ($user->can('trash users') && $user->id != $model->id) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param User $model
     *
     * @return bool
     */
    public function restore(User $user, User $model): bool
    {
        if ($user->can('restore users')) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param User $model
     *
     * @return bool
     */
    public function forceDelete(User $user, User $model): bool
    {
        if ($user->can('delete users') && $user->id != $model->id) {
            return true;
        }

        return false;
    }
}
