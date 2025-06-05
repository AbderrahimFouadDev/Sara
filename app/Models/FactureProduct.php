<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class FactureProduct extends Pivot
{
    protected $table = 'facture_product';

    protected $fillable = [
        'facture_id',
        'product_id',
        'quantity',
        'price_ht',
        'tva_rate',
        'tva_amount',
        'total_ttc'
    ];

    // Cast attributes to their native types
    protected $casts = [
        'quantity' => 'integer',
        'price_ht' => 'decimal:2',
        'tva_rate' => 'decimal:2',
        'tva_amount' => 'decimal:2',
        'total_ttc' => 'decimal:2'
    ];

    public function facture()
    {
        return $this->belongsTo(Facture::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
} 