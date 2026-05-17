<?php
// app/Models/SignatureNumerique.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SignatureNumerique extends Model
{
    use HasFactory;

    protected $table = 'signatures_numeriques';

    protected $fillable = [
        'document_type', 'document_id', 'signataire_type', 'signataire_id',
        'signataire_nom', 'signature_data', 'ip_address', 'user_agent',
        'hash_unique', 'commentaire', 'signed_at'
    ];

    protected $casts = [
        'signed_at' => 'datetime'
    ];

    /**
     * Relation polymorphique avec le document signé
     */
    public function document()
    {
        return $this->morphTo('document', 'document_type', 'document_id');
    }

    /**
     * Relation avec le signataire
     */
    public function signataire()
    {
        return $this->morphTo('signataire', 'signataire_type', 'signataire_id');
    }

    /**
     * Générer un hash unique pour la signature
     */
    public static function genererHash($documentType, $documentId, $signataireId, $signatureData)
    {
        return hash('sha256', $documentType . $documentId . $signataireId . $signatureData . now()->timestamp);
    }

    /**
     * Vérifier l'authenticité de la signature
     */
    public function verifierAuthenticite()
    {
        $hashVerification = self::genererHash(
            $this->document_type,
            $this->document_id,
            $this->signataire_id,
            $this->signature_data
        );
        
        return $this->hash_unique === $hashVerification;
    }

    /**
     * Accesseur pour QR Code data
     */
    public function getQrCodeDataAttribute()
    {
        return json_encode([
            'hash' => $this->hash_unique,
            'document' => $this->document_type . '#' . $this->document_id,
            'signataire' => $this->signataire_nom,
            'date' => $this->signed_at->format('d/m/Y H:i:s')
        ]);
    }
}