<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Controleur extends Authenticatable
{
    use HasFactory;

    protected $table = 'controleurs';

    protected $fillable = [
        'code_controleur', 'nom_complet', 'email', 'password', 'telephone',
        'region_controle', 'nombre_visites', 'statut', 'date_embauche'
    ];

    protected $hidden = ['password'];

    protected $casts = [
        'date_embauche' => 'datetime',
    ];
}