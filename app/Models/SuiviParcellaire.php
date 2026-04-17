<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuiviParcellaire extends Model
{
    use HasFactory;

    protected $table = 'suivis_parcellaires';

    protected $fillable = [
        'code_suivi', 'producteur_id', 'animateur_id', 'distribution_semence_id',
        'date_suivi', 'superficie_actuelle', 'hauteur_plantes', 'stade_croissance',
        'sante_cultures', 'taux_levée', 'problemes_constates', 'recommandations',
        'actions_prises', 'photos'
    ];

    protected $casts = [
        'date_suivi' => 'date',
        'photos' => 'array'
    ];

    public function producteur()
    {
        return $this->belongsTo(Producteur::class);
    }

    public function animateur()
    {
        return $this->belongsTo(Animateur::class);
    }

    public function distributionSemence()
    {
        return $this->belongsTo(DistributionSemence::class);
    }
}