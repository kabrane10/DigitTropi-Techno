<?php
// app/Services/SignatureService.php

namespace App\Services;

use App\Models\SignatureNumerique;
use Illuminate\Support\Facades\Storage;

class SignatureService
{
    /**
     * Sauvegarder une signature numérique
     */
    public function sauvegarder($documentType, $documentId, $signataire, $signatureData, $commentaire = null)
    {
        // Nettoyer les données Base64
        $signatureData = $this->nettoyerBase64($signatureData);
        
        // Sauvegarder l'image (optionnel)
        $path = $this->sauvegarderImage($signatureData, $documentType, $documentId);
        
        // Générer le hash unique
        $hash = SignatureNumerique::genererHash($documentType, $documentId, $signataire->id, $signatureData);
        
        // Créer l'enregistrement
        $signature = SignatureNumerique::create([
            'document_type' => $documentType,
            'document_id' => $documentId,
            'signataire_type' => get_class($signataire),
            'signataire_id' => $signataire->id,
            'signataire_nom' => $signataire->nom_complet ?? $signataire->nom ?? $signataire->name ?? 'Signataire',
            'signature_data' => $path ?? $signatureData,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'hash_unique' => $hash,
            'commentaire' => $commentaire,
            'signed_at' => now()
        ]);
        
        return $signature;
    }
    
    /**
     * Nettoyer les données Base64
     */
    private function nettoyerBase64($data)
    {
        // Enlever le préfixe "data:image/png;base64," si présent
        if (str_contains($data, 'base64,')) {
            $data = explode('base64,', $data)[1];
        }
        return $data;
    }
    
    /**
     * Sauvegarder l'image sur le disque (optionnel)
     */
    private function sauvegarderImage($base64, $documentType, $documentId)
    {
        try {
            $decoded = base64_decode($base64);
            $filename = 'signatures/' . $documentType . '_' . $documentId . '_' . time() . '.png';
            Storage::disk('public')->put($filename, $decoded);
            return $filename;
        } catch (\Exception $e) {
            return null;
        }
    }
    
    /**
     * Récupérer les signatures d'un document
     */
    public function getSignatures($documentType, $documentId)
    {
        return SignatureNumerique::where('document_type', $documentType)
            ->where('document_id', $documentId)
            ->orderBy('signed_at', 'asc')
            ->get();
    }
    
    /**
     * Vérifier si un document est entièrement signé
     */
    public function estComplete($documentType, $documentId, $signatairesRequis = [])
    {
        $signatures = $this->getSignatures($documentType, $documentId);
        $signatairesExistants = $signatures->pluck('signataire_type')->unique()->toArray();
        
        foreach ($signatairesRequis as $requis) {
            if (!in_array($requis, $signatairesExistants)) {
                return false;
            }
        }
        return true;
    }
}