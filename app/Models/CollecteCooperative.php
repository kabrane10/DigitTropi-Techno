<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollecteCooperative extends Model
{
    use HasFactory;

    protected $table = 'collectes_cooperative';

    protected $fillable = [
        'code_collecte',
        'cooperative_id',
        'credit_id',
        'date_collecte',
        'produit',
        'quantite_brute',
        'quantite_nette',
        'prix_unitaire',
        'montant_total',
        'montant_deduit',
        'montant_a_payer',
        'statut_paiement',
        'zone_collecte',
        'observations'
    ];

    protected $casts = [
        'date_collecte' => 'date',
        'quantite_brute' => 'decimal:2',
        'quantite_nette' => 'decimal:2',
        'prix_unitaire' => 'decimal:2',
        'montant_total' => 'decimal:2',
        'montant_deduit' => 'decimal:2',
        'montant_a_payer' => 'decimal:2'
    ];

    /**
     * Relation avec la coopérative
     */
    public function cooperative()
    {
        return $this->belongsTo(Cooperative::class);
    }

    /**
     * Relation avec le crédit (pour déduction)
     */
    public function credit()
    {
        return $this->belongsTo(CreditAgricole::class, 'credit_id');
    }

    /**
     * Accesseur: montant total formaté
     */
    public function getMontantTotalFormateAttribute()
    {
        return number_format($this->montant_total, 0, ',', ' ') . ' CFA';
    }

    /**
     * Accesseur: montant à payer formaté
     */
    public function getMontantAPayerFormateAttribute()
    {
        return number_format($this->montant_a_payer, 0, ',', ' ') . ' CFA';
    }

    /**
     * Accesseur: badge de statut paiement
     */
    public function getStatutPaiementBadgeAttribute()
    {
        $badges = [
            'en_attente' => '<span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">En attente</span>',
            'partiel' => '<span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">Paiement partiel</span>',
            'paye' => '<span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Payé</span>'
        ];
        
        return $badges[$this->statut_paiement] ?? $badges['en_attente'];
    }

    /**
     * Déterminer si le paiement est complet
     */
    public function getEstPayeAttribute()
    {
        return $this->statut_paiement === 'paye';
    }

    /**
     * Scope: filtrer par produit
     */
    public function scopeByProduit($query, $produit)
    {
        return $query->where('produit', $produit);
    }

    /**
     * Scope: filtrer par zone
     */
    public function scopeByZone($query, $zone)
    {
        return $query->where('zone_collecte', $zone);
    }

    /**
     * Scope: collectes d'une période
     */
    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereBetween('date_collecte', [$startDate, $endDate]);
    }

    /**
     * Scope: collectes non payées
     */
    public function scopeNonPayees($query)
    {
        return $query->where('statut_paiement', '!=', 'paye');
    }
}