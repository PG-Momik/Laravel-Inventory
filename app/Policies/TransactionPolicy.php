<?php

namespace App\Policies;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class TransactionPolicy
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
        return $user->can('viewAny transactions');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User  $user
     * @param Transaction $transaction
     *
     * @return bool
     */
    public function view(User $user, Transaction $transaction): bool
    {
        return $user->can('view transactions');
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
        return $user->can('create transactions');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User  $user
     * @param Transaction $transaction
     *
     * @return bool
     */
    public function update(User $user, Transaction $transaction): bool {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  User  $user
     * @param Transaction $transaction
     *
     * @return bool
     */
    public function delete(User $user, Transaction $transaction): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  User  $user
     * @param Transaction $transaction
     *
     * @return bool
     */
    public function restore(User $user, Transaction $transaction): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  User  $user
     * @param Transaction $transaction
     *
     * @return bool
     */
    public function forceDelete(User $user, Transaction $transaction): bool
    {
        return false;
    }
}
