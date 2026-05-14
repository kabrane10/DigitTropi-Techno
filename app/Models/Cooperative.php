<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cooperative extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom', 'code_cooperative', 'contact', 'email', 'localisation',
        'region', 'nombre_membres', 'date_creation', 'statut', 'description'
    ];

    protected $casts = [
        'date_creation' => 'date',
        'nombre_membres' => 'integer'
    ];

    public function producteurs()
    {
        return $this->hasMany(Producteur::class);
    }

    public function credits()
    {
        return $this->hasMany(CreditAgricole::class);
    }

     // NOUVELLES RELATIONS
     public function distributionsSemences()
     {
         return $this->hasMany(DistributionSemence::class);
     }
 
     public function distributionsIntrants()
     {
         return $this->hasMany(DistributionIntrantCooperative::class);
     }
 
     public function collectes()
     {
         return $this->hasMany(CollecteCooperative::class);
     }
 
     // Méthodes de calcul pour le dashboard
     public function getTotalSemencesDistribueesAttribute()
     {
         return $this->distributionsSemences()->sum('quantite');
     }
 
     public function getValeurSemencesDistribueesAttribute()
     {
         return $this->distributionsSemences()
             ->selectRaw('SUM(quantite * prix_unitaire) as total')
             ->value('total') ?? 0;
     }
 
     public function getTotalIntrantsDistribuesAttribute()
     {
         return $this->distributionsIntrants()->sum('quantite');
     }
 
     public function getValeurIntrantsDistribuesAttribute()
     {
         return $this->distributionsIntrants()
             ->selectRaw('SUM(quantite * prix_unitaire) as total')
             ->value('total') ?? 0;
     }
 
     public function getTotalCollectesAttribute()
     {
         return $this->collectes()->sum('quantite_nette');
     }
 
     public function getValeurCollectesAttribute()
     {
         return $this->collectes()->sum('montant_total');
     }
 
     public function getCreditsActifsAttribute()
     {
         return $this->credits()->where('statut', 'actif')->sum('montant_restant');
     }
 
     public function getCreditsTotalAttribute()
     {
         return $this->credits()->sum('montant_total');
     }
}