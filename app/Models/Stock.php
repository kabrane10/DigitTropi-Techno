<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = [
        'produit', 'zone', 'entrepot', 'quantite_entree', 'quantite_sortie',
        'stock_actuel', 'seuil_alerte', 'unite', 'dernier_mouvement'
    ];

    protected $casts = [
        'quantite_entree' => 'decimal:2',
        'quantite_sortie' => 'decimal:2',
        'stock_actuel' => 'decimal:2',
        'seuil_alerte' => 'decimal:2',
        'dernier_mouvement' => 'date'
    ];

    public function getEstAlerteAttribute()
    {
        return $this->stock_actuel <= $this->seuil_alerte;
    }

    public static function mettreAJour($produit, $zone, $quantite, $type)
    {
        $stock = self::firstOrCreate(
            ['produit' => $produit, 'zone' => $zone],
            ['stock_actuel' => 0, 'unite' => 'kg', 'seuil_alerte' => 100]
        );

        if ($type === 'entree') {
            $stock->quantite_entree += $quantite;
            $stock->stock_actuel += $quantite;
        } else if ($type === 'sortie') {
            $stock->quantite_sortie += $quantite;
            $stock->stock_actuel -= $quantite;
        }

        $stock->dernier_mouvement = now();
        $stock->save();

        return $stock;
    }
}