<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DistributionSemence extends Model
{
    use HasFactory;

    // Spécifier le nom correct de la table
    protected $table = 'distributions_semences';

    protected $fillable = [
        'code_distribution', 'producteur_id', 'semence_id', 'credit_id',
        'cooperative_id', 'prix_unitaire', 'montant_total',
        'quantite', 'superficie_emblevee', 'date_distribution', 'saison',
        'rendement_estime', 'observations'
    ];

    protected $casts = [
        'date_distribution' => 'date',
        'quantite' => 'decimal:2',
        'superficie_emblevee' => 'decimal:2'
    ];

    public function producteur()
    {
        return $this->belongsTo(Producteur::class);
    }

    public function semence()
    {
        return $this->belongsTo(Semence::class);
    }

    public function credit()
    {
        return $this->belongsTo(CreditAgricole::class, 'credit_id');
    }

    public function cooperative()
{
    return $this->belongsTo(Cooperative::class);
}


 public function getMontantTotalAttribute($value)
{
    if ($value) return $value;
    return $this->quantite * $this->prix_unitaire;
}
}