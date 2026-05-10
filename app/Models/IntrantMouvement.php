<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IntrantMouvement extends Model
{
    use HasFactory;

    protected $fillable = [
        'intrant_stock_id', 'type', 'quantite', 'motif', 'reference', 'user_id', 'notes'
    ];

    protected $casts = [
        'quantite' => 'decimal:2'
    ];

    public function stock()
    {
        return $this->belongsTo(IntrantStock::class, 'intrant_stock_id');
    }

    public function user()
    {
        return $this->belongsTo(Admin::class, 'user_id');
    }
}