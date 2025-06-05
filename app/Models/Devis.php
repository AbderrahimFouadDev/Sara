<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Devis extends Model
{
    use HasFactory;

    protected $table = 'devis';

    protected $fillable = [
        'client_id',
        'client_name',
        'date_devis',
        'date_validite',
        'groupe_devis',
        'montant_ht',
        'montant_tva',
        'montant_ttc',
        'numero',
        'statut',
        'conditions_paiement',
        'notes',
        'ice_client',
        'if_client',
        'rc_client'
    ];

    protected $casts = [
        'date_devis' => 'datetime',
        'date_validite' => 'datetime',
        'montant_ht' => 'decimal:2',
        'montant_tva' => 'decimal:2',
        'montant_ttc' => 'decimal:2'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'devis_product')
                    ->withPivot(['quantity', 'price_ht', 'tva_rate', 'tva_amount', 'total_ttc'])
                    ->withTimestamps();
    }

    public function facture()
    {
        return $this->hasOne(Facture::class);
    }

    public static function generateNumero()
    {
        $prefix = 'DEV';
        $year = date('Y');
        $lastDevis = self::whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();

        $sequence = $lastDevis ? intval(substr($lastDevis->numero, -4)) + 1 : 1;
        return $prefix . $year . str_pad($sequence, 4, '0', STR_PAD_LEFT);
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
}
