<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conge extends Model
{
    use HasFactory;

    protected $fillable = [
        'salarie_id',
        'type',
        'date_debut',
        'date_fin',
        'duree',
        'status',
        'motif',
        'commentaire',
        'document_justificatif',
        'approved_by',
        'approved_at'
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
        'approved_at' => 'datetime',
    ];

    // Relationship with Salarie
    public function salarie()
    {
        return $this->belongsTo(Salarie::class);
    }

    // Relationship with User (who approved)
    public function approvedByUser()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Calculate duration between dates
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($conge) {
            if (!$conge->duree) {
                $debut = \Carbon\Carbon::parse($conge->date_debut);
                $fin = \Carbon\Carbon::parse($conge->date_fin);
                $conge->duree = $debut->diffInDays($fin) + 1;
            }
        });
    }

    // Scope for current leaves
    public function scopeEnCours($query)
    {
        return $query->where('status', 'approved')
                    ->where('date_debut', '<=', now())
                    ->where('date_fin', '>=', now());
    }

    // Scope for pending leaves
    public function scopeEnAttente($query)
    {
        return $query->where('status', 'pending');
    }
} 