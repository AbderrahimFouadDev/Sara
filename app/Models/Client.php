<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_actif',
        'client_name',
        'has_tin',
        'tin',
        'autre_id',
        'points',
        'solde',
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
        'pays',
    ];

    protected $casts = [
        'client_actif' => 'boolean',
        'has_tin' => 'boolean',
        'solde' => 'decimal:2',
        'points' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $dates = [
        'created_at',
        'updated_at'
    ];
}
