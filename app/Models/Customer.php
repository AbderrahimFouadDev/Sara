<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Customer extends Authenticatable
{
    use HasFactory;

    protected $guard = 'customer';

    protected $fillable = [
        'nom',
        'prenom',
        'telephone',
        'email',
        'password',
        'entreprise',
        'adresse_entreprise',
        'secteur',
        'status',
        'photo',
    ];

    protected $hidden = [
        'password',
    ];

    protected $attributes = [
        'status' => 'active',
    ];
}
