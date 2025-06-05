<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BonLivraison extends Model
{
    use HasFactory;

    protected $table = 'bon_livraison';

    protected $fillable = [
        'client_id',
        'client_name',
        'numero_bl',
        'date_livraison',
        'adresse_livraison',
        'mode_transport',
        'reference_commande',
        'etat',
        'remarques',
    ];

    protected $casts = [
        'date_livraison' => 'datetime',
    ];

    // Relationship with Client
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'bon_livraison_product')
                    ->withPivot(['quantity', 'remarque'])
                    ->withTimestamps();
    }

    // Generate unique BL number
    public static function generateNumero()
    {
        $lastBL = self::orderBy('id', 'desc')->first();
        $year = date('Y');
        $month = date('m');

        if (!$lastBL) {
            return "BL-{$year}{$month}0001";
        }

        $lastNumber = intval(substr($lastBL->numero_bl, -4));
        $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);

        return "BL-{$year}{$month}{$newNumber}";
    }
}
