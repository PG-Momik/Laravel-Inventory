<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class ProductPolicy
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
        return $user->can('viewAny products');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param Product $product
     *
     * @return bool
     */
    public function view(User $user, Product $product): bool
    {
        return $user->can('view products');
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
        return $user->can('create products');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param Product $product
     *
     * @return bool
     */
    public function update(User $user, Product $product): bool
    {
        return $user->can('update products');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Product $product
     *
     * @return bool
     */
    public function delete(User $user, Product $product): bool
    {
        return $user->can('trash products');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param Product $product
     *
     * @return bool
     */
    public function restore(User $user, Product $product): bool
    {
        return $user->can('restore products');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param Product $product
     *
     * @return bool
     */
    public function forceDelete(User $user, Product $product): bool
    {
        return $user->can('delete products');
    }

}
