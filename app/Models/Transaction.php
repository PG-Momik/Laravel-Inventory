<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Transaction extends Model
{

    use HasFactory;

    protected $table      = "transactions";
    protected $primaryKey = "id";
    public const PURCHASE = "Purchase";
    public const SALE     = "Sale";


    /**
     * @return BelongsTo
     */
    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function products(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    /**
     * @return HasOne
     */
    public function records(): HasOne
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

    /**
     * @return HasOne
     */
    public function productInfo(): HasOne
    {
        return $this->hasOne(Product::class, 'id', 'id');
    }

    /**
     * @return HasOne
     */
    public function currentPurchasePrice(): HasOne
    {
        return $this->hasOne(Cost::class, 'id', 'cost_id');
    }

    /**
     * @return HasOne
     */
    public function currentSalesPrice(): HasOne
    {
        return $this->hasOne(Price::class, 'id', 'price_id');
    }



}
