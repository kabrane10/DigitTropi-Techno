<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DistributionIntrant extends Model
{
    use HasFactory;

    protected $table = 'distributions_intrants';

    protected $fillable = [
        'code_distribution',
        'beneficiaire_type',
        'beneficiaire_id',
        'producteur_id',
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

    // Relation polymorphique
    public function beneficiaire()
    {
        return $this->morphTo();
    }

    // Accesseurs pour le bénéficiaire
    public function getBeneficiaireNomAttribute()
    {
        if ($this->beneficiaire_type === 'App\\Models\\Cooperative' || $this->cooperative_id) {
            return $this->cooperative?->nom ?? 'N/A';
        }
        return $this->producteur?->nom_complet ?? 'N/A';
    }

    public function getBeneficiaireCodeAttribute()
    {
        if ($this->beneficiaire_type === 'App\\Models\\Cooperative' || $this->cooperative_id) {
            return $this->cooperative?->code_cooperative ?? 'N/A';
        }
        return $this->producteur?->code_producteur ?? 'N/A';
    }

    public function getBeneficiaireTypeLabelAttribute()
    {
        return ($this->beneficiaire_type === 'App\\Models\\Cooperative' || $this->cooperative_id) ? 'Coopérative' : 'Producteur';
    }

    // Relations existantes
    public function producteur()
    {
        return $this->belongsTo(Producteur::class);
    }

    public function cooperative()
    {
        return $this->belongsTo(Cooperative::class);
    }

    public function intrant()
    {
        return $this->belongsTo(Intrant::class);
    }

    public function credit()
    {
        return $this->belongsTo(CreditAgricole::class, 'credit_id');
    }

    // Accesseurs formatés
    public function getMontantFormateAttribute()
    {
        return number_format($this->montant_total, 0, ',', ' ') . ' CFA';
    }

    public function getQuantiteFormateeAttribute()
    {
        return number_format($this->quantite, 2, ',', ' ') . ' ' . ($this->intrant->unite ?? '');
    }

    // Boot method
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Déterminer le type de bénéficiaire
            if ($model->producteur_id && !$model->beneficiaire_type) {
                $model->beneficiaire_type = 'App\\Models\\Producteur';
                $model->beneficiaire_id = $model->producteur_id;
                $model->cooperative_id = null;
            } elseif ($model->cooperative_id && !$model->beneficiaire_type) {
                $model->beneficiaire_type = 'App\\Models\\Cooperative';
                $model->beneficiaire_id = $model->cooperative_id;
                $model->producteur_id = null;
            }
            
            // Calcul automatique du montant total
            if ($model->quantite && $model->prix_unitaire) {
                $model->montant_total = $model->quantite * $model->prix_unitaire;
            }
        });
    }

    // Scopes
    public function scopeByZone($query, $zone)
    {
        return $query->where('zone', $zone);
    }

    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereBetween('date_distribution', [$startDate, $endDate]);
    }
}