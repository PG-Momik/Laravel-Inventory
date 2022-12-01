<?php

namespace App\Models;

class TransactionType
{
    public const     PURCHASE = 'Purchase';
    public const     SALE     = 'Sale';
    public const     ALL      = [
        'purchase' => self::PURCHASE,
        'sales'    => self::SALE,
    ];
}
