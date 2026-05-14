<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DistributionIntrantCooperative extends Model
{
    use HasFactory;

    protected $table = 'distributions_intrants_cooperative';

    protected $fillable = [
        'code_distribution',
        'cooperative_id',
        'intrant_id',
        'credit_id',
        'quantite',
        'prix_unitaire',
        'montant_total',
        'date_distribution',
        'zone',
        'notes'
    ];

    protected $casts = [
        'date_distribution' => 'date',
        'quantite' => 'decimal:2',
        'prix_unitaire' => 'decimal:2',
        'montant_total' => 'decimal:2'
    ];

    /**
     * Relation avec la coopérative
     */
    public function cooperative()
    {
        return $this->belongsTo(Cooperative::class);
    }

    /**
     * Relation avec l'intrant
     */
    public function intrant()
    {
        return $this->belongsTo(Intrant::class);
    }

    /**
     * Relation avec le crédit (optionnel)
     */
    public function credit()
    {
        return $this->belongsTo(CreditAgricole::class, 'credit_id');
    }

    /**
     * Accesseur: montant formaté
     */
    public function getMontantFormateAttribute()
    {
        return number_format($this->montant_total, 0, ',', ' ') . ' CFA';
    }

    /**
     * Accesseur: quantité formatée
     */
    public function getQuantiteFormateeAttribute()
    {
        return number_format($this->quantite, 2, ',', ' ') . ' ' . ($this->intrant->unite ?? '');
    }

    /**
     * Scope: filtrer par zone
     */
    public function scopeByZone($query, $zone)
    {
        return $query->where('zone', $zone);
    }

    /**
     * Scope: distributions d'une période
     */
    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereBetween('date_distribution', [$startDate, $endDate]);
    }
}