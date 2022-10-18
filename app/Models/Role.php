<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{

    use HasFactory;

    protected $table      = "roles";
    protected $primaryKey = "id";

    /**
     * @return HasMany
     */
    public function users(): HasMany
    {
        return $this->hasMany(Customer::class, 'user_id', 'id');
    }

}
