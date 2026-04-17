<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Achat extends Model
{
    use HasFactory;

    protected $table = 'achats';

    protected $fillable = [
        'code_achat', 'collecte_id', 'date_achat', 'acheteur',
        'quantite', 'prix_achat', 'montant_total', 'mode_paiement',
        'reference_facture', 'statut', 'notes'
    ];

    protected $casts = [
        'date_achat' => 'date',
        'quantite' => 'decimal:2',
        'prix_achat' => 'decimal:2',
        'montant_total' => 'decimal:2'
    ];

    
    public function collecte()
    {
        return $this->belongsTo(Collecte::class, 'collecte_id');
    }
}