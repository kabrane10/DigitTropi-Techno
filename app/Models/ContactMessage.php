<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom', 'email', 'telephone', 'sujet', 'message', 'lu', 'date_envoi'
    ];

    protected $casts = [
        'lu' => 'boolean',
        'date_envoi' => 'datetime'
    ];

    // Scope pour les messages non lus
    public function scopeNonLus($query)
    {
        return $query->where('lu', false);
    }
}