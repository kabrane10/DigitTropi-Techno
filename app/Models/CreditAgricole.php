<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreditAgricole extends Model
{
    use HasFactory;

    protected $table = 'credits_agricoles';

    protected $fillable = [
        'code_credit', 'producteur_id', 'cooperative_id', 'montant_total',
        'montant_restant', 'taux_interet', 'duree_mois', 'date_octroi',
        'date_echeance', 'statut', 'conditions', 'observations'
    ];

    protected $casts = [
        'date_octroi' => 'date',
        'date_echeance' => 'date',
        'montant_total' => 'decimal:2',
        'montant_restant' => 'decimal:2',
        'taux_interet' => 'decimal:2'
    ];

    public function producteur()
    {
        return $this->belongsTo(Producteur::class);
    }

    public function cooperative()
    {
        return $this->belongsTo(Cooperative::class);
    }

    public function remboursements()
    {
        return $this->hasMany(Remboursement::class, 'credit_id');
    }

    public function collectes()
    {
        return $this->hasMany(Collecte::class, 'credit_id');
    }

    public function distributionsSemences()
    {
        return $this->hasMany(DistributionSemence::class, 'credit_id');
    }

    public function getMontantRembourseAttribute()
    {
        return $this->remboursements()->sum('montant');
    }

    public function getEstEnRetardAttribute()
    {
        if ($this->statut !== 'actif') return false;
        return now()->gt($this->date_echeance) && $this->montant_restant > 0;
    }

    public function getJoursRetardAttribute()
    {
        if (!$this->est_en_retard) return 0;
        return now()->diffInDays($this->date_echeance);
    }

    public function getTauxRemboursementAttribute()
    {
        if ($this->montant_total == 0) return 0;
        return ($this->montant_rembourse / $this->montant_total) * 100;
    }
}