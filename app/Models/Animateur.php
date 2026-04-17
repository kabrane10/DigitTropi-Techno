<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Animateur extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $guard  = 'animateurs';

    protected $fillable = [
        'code_animateur', 'nom_complet', 'email', 'password', 'contact',
        'zone_responsabilite', 'region', 'nombre_producteurs_suivis',
        'statut', 'date_embauche', 'qualifications'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'date_embauche' => 'date',
        'nombre_producteurs_suivis' => 'integer'
    ];

    // Relation avec les agents terrain
    public function agents()
    {
        return $this->hasMany(AgentTerrain::class, 'superviseur_id');
    }

    // Relation avec les suivis parcellaires
    public function suivis()
    {
        return $this->hasMany(SuiviParcellaire::class);
    }
}