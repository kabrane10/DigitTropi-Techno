<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Intrant extends Model
{
    use HasFactory;

    protected $fillable = [
        'code_intrant', 'nom', 'type', 'unite', 'prix_unitaire', 'description', 'est_actif'
    ];

    protected $casts = [
        'est_actif' => 'boolean',
        'prix_unitaire' => 'decimal:2'
    ];

    public function stocks()
    {
        return $this->hasMany(IntrantStock::class);
    }

    public function getTypeLabelAttribute()
    {
        return [
            'engrais' => ' Engrais',
            'pesticide' => ' Pesticide',
            'herbicide' => ' Herbicide',
            'semence' => ' Semence',
            'autre' => ' Autre'
        ][$this->type] ?? $this->type;
    }
}