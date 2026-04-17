<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producteur extends Model
{
    use HasFactory;

    protected $table = 'producteurs';

    protected $fillable = [
        'code_producteur', 'nom_complet', 'contact', 'email', 'localisation',
        'region', 'culture_pratiquee', 'superficie_totale', 'cooperative_id',
        'agent_terrain_id', 'statut', 'date_enregistrement', 'notes'
    ];

    protected $casts = [
        'date_enregistrement' => 'date',
        'superficie_totale' => 'decimal:2'
    ];

    public function agentTerrain()
{
    return $this->belongsTo(AgentTerrain::class);
}

    public function cooperative()
    {
        return $this->belongsTo(Cooperative::class);
    }

    public function credits()
    {
        return $this->hasMany(CreditAgricole::class);
    }

    // Relation avec les distributions de semences 
    public function distributions()
    {
        return $this->hasMany(DistributionSemence::class);
    }

    public function distributionSemence()
    {
        return $this->hasMany(distributions_semence::class);
    }

    public function suivisParcellaires()
    {
        return $this->hasMany(SuiviParcellaire::class);
    }

    public function collectes()
    {
        return $this->hasMany(Collecte::class);
    }

    // Accesseurs
    public function getCreditsActifsMontantAttribute()
    {
        return $this->credits()->where('statut', 'actif')->sum('montant_restant');
    }

    public function getProductionTotaleAttribute()
    {
        return $this->collectes()->sum('quantite_nette');
    }

    public function getRendementMoyenAttribute()
    {
        $distributions = $this->distributionsSemences()->sum('superficie_emblevee');
        if ($distributions == 0) return 0;
        return ($this->production_totale / $distributions) * 1000; // en kg/ha
    }
}