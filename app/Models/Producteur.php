<?php

namespace App\Models;

use SignableTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producteur extends Model
{
    use HasFactory;
    use SignableTrait;

    protected $table = 'producteurs';

    protected $fillable = [
        'code_producteur', 'nom_complet', 'contact', 'email', 'localisation',
        'commune', 'latitude', 'longitude',
        'region', 'culture_pratiquee', 'superficie_totale', 'cooperative_id',
        'agent_terrain_id', 'statut', 'date_enregistrement', 'notes'
    ];

    protected $casts = [
        'date_enregistrement' => 'date',
        'superficie_totale' => 'decimal:2',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7'
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

     // Accesseur pour obtenir la position formatée
     public function getPositionAttribute()
     {
         if ($this->latitude && $this->longitude) {
             return $this->latitude . ', ' . $this->longitude;
         }
         return null;
     }
 
     // Accesseur pour obtenir l'adresse complète
     public function getAdresseCompleteAttribute()
     {
         $parts = [];
         if ($this->localisation) $parts[] = $this->localisation;
         if ($this->commune) $parts[] = $this->commune;
         if ($this->region) $parts[] = $this->region;
         return implode(', ', $parts);
     }
 
     // Scope pour filtrer par commune
     public function scopeByCommune($query, $commune)
     {
         return $query->where('commune', $commune);
     }
 
     // Scope pour trouver les producteurs à proximité
     public function scopeProches($query, $latitude, $longitude, $distance = 10)
     {
         return $query->whereRaw(
             "(6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) < ?",
             [$latitude, $longitude, $latitude, $distance]
         );
     }
 
}