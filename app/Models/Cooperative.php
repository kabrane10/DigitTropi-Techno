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
}