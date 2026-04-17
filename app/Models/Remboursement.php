<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Remboursement extends Model
{
    use HasFactory;

    protected $table = 'remboursements';

    protected $fillable = [
        'code_remboursement', 'credit_id', 'date_remboursement', 'montant',
        'type', 'mode_paiement', 'reference', 'notes'
    ];

    protected $casts = [
        'date_remboursement' => 'date',
        'montant' => 'decimal:2'
    ];

    // Relation avec le crédit
    public function credit()
    {
        return $this->belongsTo(CreditAgricole::class, 'credit_id');
    }
}