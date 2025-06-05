<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fournisseur extends Model
{
    use HasFactory;

    protected $fillable = [
        'fournisseur_actif',
        'fournisseur_name',
        'has_tin',
        'tin',
        'autre_id_vendeur',
        'debut_balance_fournisseur',
        'contact_personne',
        'phone_code',
        'telephone',
        'fax_code',
        'fax',
        'email',
        'website',
        'linkedin',
        'facebook',
        'twitter',
        'google',
        'adresse',
        'complement',
        'adresse_sup',
        'immeuble',
        'region',
        'district',
        'ville',
        'code_postal',
        'pays'
    ];

    protected $casts = [
        'fournisseur_actif' => 'boolean',
        'has_tin' => 'boolean',
        'debut_balance_fournisseur' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    protected $dates = [
        'created_at',
        'updated_at'
    ];
} 