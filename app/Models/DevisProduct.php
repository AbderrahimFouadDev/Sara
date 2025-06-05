<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DevisProduct extends Model
{
    use HasFactory;

    protected $table = 'devis_product';

    protected $fillable = [
        'devis_id',
        'product_id',
        'quantity',
        'price',
        'price_ht',
        'tva_rate',
        'tva_amount',
        'total_ttc'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'price_ht' => 'decimal:2',
        'tva_rate' => 'decimal:2',
        'tva_amount' => 'decimal:2',
        'total_ttc' => 'decimal:2'
    ];

    public function devis()
    {
        return $this->belongsTo(Devis::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
} 