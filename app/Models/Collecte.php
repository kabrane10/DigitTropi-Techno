<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Collecte extends Model
{
    use HasFactory;

    protected $table = 'collectes';

    protected $fillable = [
        'code_collecte', 'producteur_id', 'credit_id', 'date_collecte',
        'produit', 'quantite_brute', 'quantite_nette', 'prix_unitaire',
        'montant_total', 'montant_deduict', 'montant_a_payer',
        'statut_paiement', 'zone_collecte', 'observations'
    ];

    protected $casts = [
        'date_collecte' => 'date',
        'quantite_brute' => 'decimal:2',
        'quantite_nette' => 'decimal:2',
        'prix_unitaire' => 'decimal:2',
        'montant_total' => 'decimal:2',
        'montant_deduict' => 'decimal:2',
        'montant_a_payer' => 'decimal:2'
    ];

    public function producteur()
    {
        return $this->belongsTo(Producteur::class);
    }


    public function credit()
    {
        return $this->belongsTo(CreditAgricole::class, 'credit_id');
    }

    
    public function achat()
    {
        return $this->hasOne(Achat::class, 'collecte_id');
    }
}