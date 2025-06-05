<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Remboursement extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'facture_id',
        'date_remboursement',
        'montant',
        'motif',
        'statut'
    ];

    protected $casts = [
        'date_remboursement' => 'date',
        'montant' => 'decimal:2'
    ];

    /**
     * Get the client that owns the remboursement.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the facture associated with the remboursement.
     */
    public function facture(): BelongsTo
    {
        return $this->belongsTo(Facture::class);
    }

    /**
     * Scope a query to only include remboursements for a specific client.
     */
    public function scopeForClient($query, $clientId)
    {
        return $query->where('client_id', $clientId);
    }

    /**
     * Scope a query to filter by status.
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('statut', $status);
    }

    /**
     * Scope a query to filter by date range.
     */
    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('date_remboursement', [$startDate, $endDate]);
    }
} 