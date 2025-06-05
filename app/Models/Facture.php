<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facture extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero',
        'id_fournisseur',
        'fournisseur_name',
        'date_facture',
        'date_echeance',
        'groupe_facture',
        'montant',
        'montant_ht',
        'montant_tva',
        'montant_ttc',
        'statut',
        'notes'
    ];

    protected $casts = [
        'date_facture' => 'datetime',
        'date_echeance' => 'datetime',
        'montant' => 'decimal:2',
        'montant_ht' => 'decimal:2',
        'montant_tva' => 'decimal:2',
        'montant_ttc' => 'decimal:2'
    ];

    // Relationship with Fournisseur
    public function fournisseur()
    {
        return $this->belongsTo(Fournisseur::class, 'id_fournisseur');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'facture_product')
                    ->withPivot(['quantity', 'price_ht', 'tva_rate', 'tva_amount', 'total_ttc'])
                    ->withTimestamps();
    }

    public function calculateTotals()
    {
        $this->montant_ht = $this->products->sum(function($product) {
            return $product->pivot->price_ht * $product->pivot->quantity;
        });
        
        $this->montant_tva = $this->products->sum(function($product) {
            return $product->pivot->tva_amount;
        });
        
        $this->montant_ttc = $this->montant_ht + $this->montant_tva;
        
        $this->save();
    }

    // Generate unique invoice number
    public static function generateNumero()
    {
        $lastFacture = self::orderBy('id', 'desc')->first();
        $year = date('Y');
        $month = date('m');
        
        if (!$lastFacture) {
            return "FAC-{$year}{$month}0001";
        }

        $lastNumber = intval(substr($lastFacture->numero, -4));
        $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        
        return "FAC-{$year}{$month}{$newNumber}";
    }
} 