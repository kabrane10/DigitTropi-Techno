<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'type', 'title', 'message', 'url', 'is_read', 'user_id'
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'created_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(Admin::class, 'user_id');
    }

    public function scopeNonLues($query)
    {
        return $query->where('is_read', false);
    }
}