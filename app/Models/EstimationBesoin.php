<?php

namespace App\Models;

use App\Traits\SignableTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstimationBesoin extends Model
{
    use HasFactory;
    use SignableTrait;

    protected $table = 'estimation_besoins';

    protected $fillable = [
        'code_estimation',
        'producteur_id',
        'semence_id',
        'quantite_estimee',
        'superficie_prevue',
        'credit_montant',
        'date_estimation',
        'statut',
        'intrants',
        'cout_semences',
        'cout_intrants',
        'autres_frais',
        'total_estimation',
        'observations'
    ];

    protected $casts = [
        'date_estimation' => 'date',
        'quantite_estimee' => 'decimal:2',
        'superficie_prevue' => 'decimal:2',
        'credit_montant' => 'decimal:2',
        'intrants' => 'array',
        'cout_semences' => 'decimal:2',
        'cout_intrants' => 'decimal:2',
        'autres_frais' => 'decimal:2',
        'total_estimation' => 'decimal:2',
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