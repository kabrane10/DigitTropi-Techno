<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstimationBesoin extends Model
{
    use HasFactory;

    protected $table = 'estimation_besoins';

    protected $fillable = [
        'code_estimation', 'producteur_id', 'semence_id', 'quantite_estimee',
        'superficie_prevue', 'credit_montant', 'intrants', 'date_estimation', 'statut', 'observations'
    ];

    protected $casts = [
        'date_estimation' => 'date',
        'quantite_estimee' => 'decimal:2',
        'superficie_prevue' => 'decimal:2',
        'credit_montant' => 'decimal:2',
         'intrants' => 'array'
    ];

    public function producteur()
    {
        return $this->belongsTo(Producteur::class);
    }

    public function semence()
    {
        return $this->belongsTo(Semence::class);
    }

    public function getStatutLabelAttribute()
    {
        return [
            'en_attente' => ' En attente',
            'approuve' => ' Approuvé',
            'rejete' => ' Rejeté'
        ][$this->statut] ?? $this->statut;
    }
}