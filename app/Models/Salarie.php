<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Salarie extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom_complet',
        'cin',
        'cnss',
        'poste',
        'departement',
        'statut',
        'date_embauche',
        'salaire_base',
        'type_contrat',
        'date_debut_contrat',
        'date_fin_contrat',
        'document_cin',
        'document_cnss',
        'document_contrat',
        'photo',
        'dernier_bulletin',
        'jours_conges_restants',
        'jours_absences_non_justifiees',
        'historique_promotions',
        'historique_salaires',
        'historique_contrats',
        'certificat_travail',
        'solde_tout_compte',
        'exporte_dsn',
        'exporte_cnss',
        'dernier_net_paye',
        'mois_dernier_paye',
    ];

    protected $casts = [
        'date_embauche' => 'date',
        'date_debut_contrat' => 'date',
        'date_fin_contrat' => 'date',
        'salaire_base' => 'decimal:2',
        'dernier_bulletin' => 'array',
        'historique_promotions' => 'array',
        'historique_salaires' => 'array',
        'historique_contrats' => 'array',
        'exporte_dsn' => 'boolean',
        'exporte_cnss' => 'boolean',
        'dernier_net_paye' => 'decimal:2',
    ];

    // Scope to get active employees
    public function scopeActif($query)
    {
        return $query->where('statut', 'actif');
    }

    // Scope to get employees on leave
    public function scopeEnConge($query)
    {
        return $query->where('statut', 'congé');
    }

    // Scope to get employees by department
    public function scopeByDepartement($query, $departement)
    {
        return $query->where('departement', $departement);
    }

    // Get the employee's full name
    public function getNomCompletAttribute($value)
    {
        return ucwords($value);
    }

    // Get the CIN document URL
    public function getDocumentCinUrlAttribute()
    {
        return $this->document_cin ? asset('storage/' . $this->document_cin) : null;
    }

    // Get the CNSS document URL
    public function getDocumentCnssUrlAttribute()
    {
        return $this->document_cnss ? asset('storage/' . $this->document_cnss) : null;
    }

    // Get the contract document URL
    public function getDocumentContratUrlAttribute()
    {
        return $this->document_contrat ? asset('storage/' . $this->document_contrat) : null;
    }

    // Get the remaining leave days
    public function getJoursCongesRestantsAttribute($value)
    {
        return $value ?? 0;
    }

    // Get the unjustified absence days
    public function getJoursAbsencesNonJustifieesAttribute($value)
    {
        return $value ?? 0;
    }

    // Add a promotion to history
    public function ajouterPromotion($ancienPoste, $nouveauPoste)
    {
        $historique = $this->historique_promotions ?? [];
        $historique[] = [
            'date' => now()->toDateString(),
            'ancien_poste' => $ancienPoste,
            'nouveau_poste' => $nouveauPoste
        ];
        $this->update(['historique_promotions' => $historique]);
    }

    // Add a salary change to history
    public function ajouterChangementSalaire($nouveauMontant)
    {
        $historique = $this->historique_salaires ?? [];
        $historique[] = [
            'date' => now()->toDateString(),
            'ancien_montant' => $this->salaire_base,
            'nouveau_montant' => $nouveauMontant
        ];
        $this->update([
            'historique_salaires' => $historique,
            'salaire_base' => $nouveauMontant
        ]);
    }

    // Add a contract to history
    public function ajouterContrat($type, $dateDebut, $dateFin = null)
    {
        $historique = $this->historique_contrats ?? [];
        $historique[] = [
            'date_debut' => $dateDebut,
            'date_fin' => $dateFin,
            'type' => $type
        ];
        $this->update(['historique_contrats' => $historique]);
    }
} 