<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Collecte extends Model
{
    use HasFactory;

    protected $table = 'collectes';

    protected $fillable = [
        'code_collecte', 'producteur_id', 'cooperative_id', 'beneficiaire_type', 'beneficiaire_id',
        'credit_id', 'date_collecte', 'produit', 'quantite_brute', 'quantite_nette', 
        'prix_unitaire', 'montant_total', 'montant_deduict', 'montant_a_payer',
        'statut_paiement', 'zone_collecte', 'observations'
    ];

    protected $casts = [
        'date_collecte' => 'date',
        'quantite_brute' => 'decimal:2',
        'quantite_nette' => 'decimal:2',
        'prix_unitaire' => 'decimal:2',
        'montant_total' => 'decimal:2',
        'montant_deduict' => 'decimal:2',
        'montant_a_payer' => 'decimal:2'
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

     // Relations 
     public function producteur()
     {
         return $this->belongsTo(Producteur::class);
     }
 
     public function cooperative()
     {
         return $this->belongsTo(Cooperative::class);
     }
 
     public function credit()
     {
         return $this->belongsTo(CreditAgricole::class, 'credit_id');
     }
 
     public function achat()
     {
         return $this->hasOne(Achat::class, 'collecte_id');
     }
 
     // Boot method pour définir automatiquement le beneficiaire_type
     protected static function boot()
     {
         parent::boot();
 
         static::creating(function ($model) {
             if ($model->producteur_id && !$model->beneficiaire_type) {
                 $model->beneficiaire_type = 'App\\Models\\Producteur';
                 $model->beneficiaire_id = $model->producteur_id;
             } elseif ($model->cooperative_id && !$model->beneficiaire_type) {
                 $model->beneficiaire_type = 'App\\Models\\Cooperative';
                 $model->beneficiaire_id = $model->cooperative_id;
             }
         });
     }
}