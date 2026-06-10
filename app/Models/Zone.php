<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    /**
     * Définit la relation "un-à-plusieurs" avec les stocks d'intrants.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function intrantStocks()
    {
        // Une zone peut avoir plusieurs enregistrements de stock d'intrants.
        // La liaison se fait sur la colonne 'zone' de la table 'intrant_stocks'
        // et la colonne 'name' de la table 'zones'.
        return $this->hasMany(IntrantStock::class, 'zone', 'name');
    }
}
