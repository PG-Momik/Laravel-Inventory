<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{

    use HasFactory;

    protected $table      = "products";
    protected $primaryKey = "id";

    /**
     * @return BelongsTo
     */
    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class, 'registered_by', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function categories(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'product_id', 'id');
    }


}
