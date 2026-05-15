<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Signature extends Model
{
    use HasFactory;

    protected $fillable = [
        'signable_type', 'signable_id', 'signataire_type', 'signataire_id',
        'signataire_nom', 'signature_data', 'ip_address', 'signed_at'
    ];

    protected $casts = [
        'signed_at' => 'datetime'
    ];

    public function signable()
    {
        return $this->morphTo();
    }

    public function hasSignature()
    {
        return !is_null($this->signature_data);
    }
}