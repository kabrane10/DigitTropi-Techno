<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class AgentTerrain extends Authenticatable
{
    use HasFactory;

    protected $table = 'agents_terrain';

    protected $fillable = [
        'code_agent',
        'nom_complet',
        'email',
        'password',
        'telephone',
        'zone_affectation',
        'superviseur_id',
        'producteurs_enregistres',
        'statut',
        'date_embauche'
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'date_embauche' => 'date',
        'producteurs_enregistres' => 'integer'
    ];

    public function superviseur()
    {
        return $this->belongsTo(Animateur::class, 'superviseur_id');
    }

    public function producteurs()
    {
        return $this->hasMany(Producteur::class, 'agent_terrain_id');
    }
    
}