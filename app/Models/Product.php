<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nom',
        'is_service',
        'description',
        'categorie_id',
        'fournisseur_id',
        'prix_achat',
        'prix_vente',
        'quantite',
        'quantite_min',
        'code_barre',
        'reference',
        'unite'
    ];

    protected $casts = [
        'is_service' => 'boolean',
        'prix_achat' => 'decimal:2',
        'prix_vente' => 'decimal:2',
        'quantite' => 'integer',
        'quantite_min' => 'integer'
    ];

    // Relationships
    public function categorie()
    {
        return $this->belongsTo(Category::class, 'categorie_id');
    }

    public function fournisseur()
    {
        return $this->belongsTo(Fournisseur::class, 'fournisseur_id');
    }
} 