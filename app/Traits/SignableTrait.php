<?php

namespace App\Traits;

use App\Models\Signature;

trait SignableTrait
{
    public function signatures()
    {
        return $this->morphMany(Signature::class, 'signable');
    }

    public function sign($signataireType, $signataireId, $signataireNom, $signatureData = null)
    {
        return $this->signatures()->create([
            'signataire_type' => $signataireType,
            'signataire_id' => $signataireId,
            'signataire_nom' => $signataireNom,
            'signature_data' => $signatureData,
            'ip_address' => request()->ip(),
            'signed_at' => now()
        ]);
    }

    public function hasSignatureFrom($signataireType)
    {
        return $this->signatures()->where('signataire_type', $signataireType)->exists();
    }

    public function getSignatureFrom($signataireType)
    {
        return $this->signatures()->where('signataire_type', $signataireType)->first();
    }

    public function areAllSignaturesPresent($signataires = ['producteur', 'agent'])
    {
        $signaturesCount = $this->signatures()->whereIn('signataire_type', $signataires)->count();
        return $signaturesCount === count($signataires);
    }
}