<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Actualite extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre', 'slug', 'contenu', 'image_couverture', 'categorie',
        'date_publication', 'date_fin', 'lieu', 'est_en_avant', 'est_publie'
    ];

    protected $casts = [
        'date_publication' => 'date',
        'date_fin' => 'date',
        'est_en_avant' => 'boolean',
        'est_publie' => 'boolean'
    ];

    public function scopePublie($query)
    {
        return $query->where('est_publie', true)
                     ->where('date_publication', '<=', now());
    }

    public function scopeEnCours($query)
    {
        return $query->where('date_publication', '<=', now())
                     ->where(function($q) {
                         $q->whereNull('date_fin')
                           ->orWhere('date_fin', '>=', now());
                     });
    }

    public function getExcerptAttribute()
    {
        return Str::limit(strip_tags($this->contenu), 150);
    }

    public function getImageUrlAttribute()
    {
        return $this->image_couverture ? asset('storage/' . $this->image_couverture) : null;
    }
}