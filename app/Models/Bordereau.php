<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bordereau extends Model
{
    use HasFactory;

    protected $table = 'bordereaux';

    protected $fillable = [
        'code_bordereau', 'type', 'reference_id', 'reference_type',
        'date_emission', 'contenu', 'emetteur', 'destinataire',
        'statut', 'observations'
    ];

    protected $casts = [
        'date_emission' => 'date',
        'contenu' => 'array'
    ];

    public function reference()
    {
        return $this->morphTo();
    }

    public function getTypeLabelAttribute()
    {
        return [
            'collecte' => 'Bordereau de Collecte',
            'achat' => 'Bordereau d\'Achat',
            'chargement' => 'Bordereau de Chargement',
            'livraison' => 'Bordereau de Livraison'
        ][$this->type] ?? $this->type;
    }

    public function getStatutLabelAttribute()
    {
        return [
            'valide' => 'Validé',
            'annule' => 'Annulé',
            'en_attente' => 'En attente'
        ][$this->statut] ?? $this->statut;
    }

    public function getStatutColorAttribute()
    {
        return [
            'valide' => 'green',
            'annule' => 'red',
            'en_attente' => 'yellow'
        ][$this->statut] ?? 'gray';
    }
}