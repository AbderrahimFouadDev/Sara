<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BonLivraisonProduct extends Model
{
    use HasFactory;

    protected $table = 'bon_livraison_product';

    protected $fillable = [
        'bon_livraison_id',
        'product_id',
        'quantity',
        'price_ht',
        'remise_percent',
        'remise_amount',
        'total_ht',
        'tva_rate',
        'tva_amount',
        'total_ttc'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price_ht' => 'decimal:2',
        'remise_percent' => 'decimal:2',
        'remise_amount' => 'decimal:2',
        'total_ht' => 'decimal:2',
        'tva_rate' => 'decimal:2',
        'tva_amount' => 'decimal:2',
        'total_ttc' => 'decimal:2'
    ];

    public function bonLivraison()
    {
        return $this->belongsTo(BonLivraison::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function calculateTotals()
    {
        // Calculate remise amount
        $this->remise_amount = ($this->price_ht * $this->quantity) * ($this->remise_percent / 100);
        
        // Calculate total HT after remise
        $this->total_ht = ($this->price_ht * $this->quantity) - $this->remise_amount;
        
        // Calculate TVA amount
        $this->tva_amount = $this->total_ht * ($this->tva_rate / 100);
        
        // Calculate total TTC
        $this->total_ttc = $this->total_ht + $this->tva_amount;

        return $this;
    }
} 