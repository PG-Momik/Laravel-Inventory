<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    use HasFactory;
    use SoftDeletes;

    protected $table      = "users";
    public    $primaryKey = "id";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];



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

    public function productEntries(): HasMany
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
     * Returns all sales transactions of user
     *
     * @return HasMany
     */
    public function sales(): HasMany
    {
        return $this->hasMany(Transaction::class, 'user_id', 'id')
            ->where('type', '=', Transaction::PURCHASE);
    }

    /**
     *Returns all purchase transactions of user
     *
     * @return HasMany
     */
    public function purchases(): HasMany
    {
        return $this->hasMany(Transaction::class, 'user_id', 'id')
            ->where('type', '=', Transaction::SALE);
    }




















}
