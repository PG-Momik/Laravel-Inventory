<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Transaction extends Model
{
    use HasFactory;

    protected $table      = "transactions";
    protected $primaryKey = "id";


    /**
     * Returns user who made the transaction.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Returns product detail of the transaction.
     *
     * @return HasOne
     */
    public function product(): hasOne
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

    /**
     * Returns purchase price during transaction of a product.
     *
     * @return HasOne
     */
    public function purchasePriceDuringTransaction(): HasOne
    {
        return $this->hasOne(PurchasePrice::class, 'id', 'purchase_price_id');
    }

    /**
     * Returns sales price during transaction of a product.
     *
     * @return HasOne
     */
    public function salesPriceDuringTransaction(): HasOne
    {
        return $this->hasOne(SalesPrice::class, 'id', 'sales_price_id');
    }
}
