<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IntrantStock extends Model
{
    use HasFactory;

    protected $table = 'intrant_stocks';

    protected $fillable = [
        'intrant_id', 'zone', 'stock_actuel', 'seuil_alerte', 'unite', 'emplacement'
    ];

    protected $casts = [
        'stock_actuel' => 'decimal:2',
        'seuil_alerte' => 'decimal:2'
    ];

    public function intrant()
    {
        return $this->belongsTo(Intrant::class);
    }

    public function mouvements()
    {
        return $this->hasMany(IntrantMouvement::class);
    }

    public function getEstCritiqueAttribute()
    {
        return $this->stock_actuel <= $this->seuil_alerte;
    }

    public function getNiveauAlerteAttribute()
    {
        $pourcentage = ($this->stock_actuel / $this->seuil_alerte) * 100;
        if ($pourcentage <= 20) return 'critique';
        if ($pourcentage <= 50) return 'attention';
        return 'normal';
    }
}