<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Semence extends Model
{
    use HasFactory;

    protected $table = 'semences';

    protected $fillable = [
        'code_semence', 'nom', 'variete', 'type', 'prix_unitaire',
        'stock_disponible', 'unite', 'description'
    ];

    protected $casts = [
        'prix_unitaire' => 'decimal:2',
        'stock_disponible' => 'decimal:2'
    ];

    // Relation avec les distributions
    public function distributions()
    {
        return $this->hasMany(DistributionSemence::class);
    }

    // Accesseur pour le libellé complet
    public function getLibelleAttribute()
    {
        return "{$this->nom} ({$this->variete}) - {$this->stock_disponible} {$this->unite}";
    }

    // Scope pour les semences en stock
    public function scopeEnStock($query)
    {
        return $query->where('stock_disponible', '>', 0);
    }
}