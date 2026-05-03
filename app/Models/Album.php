<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Album extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre', 'slug', 'categorie', 'lieu', 'date_evenement',
        'description', 'couverture', 'est_publie'
    ];

    protected $casts = [
        'date_evenement' => 'date',
        'est_publie' => 'boolean'
    ];

    public function images()
    {
        return $this->hasMany(Galerie::class);
    }

    public function getCouvertureUrlAttribute()
    {
        return $this->couverture ? asset('storage/' . $this->couverture) : null;
    }

    public function scopePublie($query)
    {
        return $query->where('est_publie', true);
    }

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($album) {
            $album->slug = Str::slug($album->titre) . '-' . uniqid();
        });
    }
}