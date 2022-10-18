<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{

    use HasFactory;
    use SoftDeletes;

    protected $table      = "users";
    public    $primaryKey = "id";
    protected $hidden     = ['password', 'remember_token', 'two_factor_secret', 'two_factor_recovery_codes'];


    /**
     *
     * @return BelongsTo
     */
    public function roles(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

    /**
     * Returns Products
     * @return HasMany
     */

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'registered_by', 'id');
    }

    /**
     * Returns all Transactions
     * @return HasMany
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'user_id', 'id');
    }

    /**
     * Returns Transactions that increases inventory count.
     * @return HasMany
     */
    public function addedTransactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'user_id', 'id')
            ->where('type', '=', 'Add');
    }

    /**
     *
     * Return Transactions that reduces inventory count.
     * @return HasMany
     */
    public function subtractedTransaction(): HasMany
    {
        return $this->hasMany(Transaction::class, 'user_id', 'id')
            ->where('type', '=', 'Remove');
    }

}
