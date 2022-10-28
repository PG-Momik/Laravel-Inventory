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
     *Returns user role.
     * @return BelongsTo
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

    /**
     * Returns products registered by user.
     * @return HasMany
     */
    public function registeredProducts(): HasMany
    {
        return $this->hasMany(Product::class, 'registered_by', 'id');
    }

    /**
     * Returns all user transactions.
     * @return HasMany
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'user_id', 'id')->latest();
    }


    /**
     * Returns all user purchases.
     * @return HasMany
     */
    public function purchases(): HasMany
    {
        return $this->hasMany(Transaction::class, 'user_id', 'id')
            ->where('type', '=', Transaction::TYPE[0]);
    }

    /**
     * Returns all user sales.
     * @return HasMany
     */
    public function sales(): HasMany
    {
        return $this->hasMany(Transaction::class, 'user_id', 'id')
            ->where('type', '=', Transaction::TYPE[1]);
    }


}
