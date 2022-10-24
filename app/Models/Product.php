<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{

    use HasFactory;
    use SoftDeletes;

    protected $table      = "products";
    protected $primaryKey = "id";

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'registered_by', 'id')->withTrashed();
    }

    /**
     * @return BelongsTo
     */
    public function category(): BelongsTo
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

    /**
     * @return HasMany
     */
    public function purchasePrices(): HasMany
    {
        return $this->hasMany(Cost::class, 'product_id', 'id');
    }

    /**
     * @return HasOne
     */
    public function latestPurchasePrice(): HasOne
    {
        return $this->hasOne(Cost::class, 'product_id', 'id')->latest();
    }


    /**
     * @return HasMany
     */
    public function salesPrices(): HasMany
    {
        return $this->hasMany(Price::class, 'product_id', 'id');
    }

    /**
     * @return HasOne
     */
    public function latestSalesPrice(): HasOne
    {
        return $this->hasOne(Price::class, 'product_id', 'id')->latest();
    }
}
