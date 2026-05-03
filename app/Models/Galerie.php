<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Galerie extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre', 'image', 'description', 'categorie', 
        'lieu', 'date_prise', 'est_publie', 'ordre', 'album_id'
    ];

    protected $casts = [
        'date_prise' => 'date',
        'est_publie' => 'boolean'
    ];

    public function album()
    {
        return $this->belongsTo(Album::class);
    }

    public function getImageUrlAttribute()
    {
        // return asset('storage/' . $this->image);
        return $this->image;
    }

    public function scopePublie($query)
    {
        return $query->where('est_publie', true);
    }

}