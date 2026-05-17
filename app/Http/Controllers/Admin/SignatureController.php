<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SignatureNumerique;
use App\Services\SignatureService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SignatureController extends Controller
{
    protected $signatureService;

    public function __construct(SignatureService $signatureService)
    {
        $this->signatureService = $signatureService;
    }

    /**
     * Vérifier l'authenticité d'une signature via son hash
     */
    public function verifier($hash)
    {
        $signature = SignatureNumerique::where('hash_unique', $hash)->firstOrFail();
        
        $estValide = $signature->verifierAuthenticite();
        
        if ($estValide) {
            return view('admin.signatures.verification', compact('signature'));
        }
        
        return abort(404, 'Signature invalide ou corrompue');
    }

    /**
     * Générer un QR code pour la signature
     */
    public function qrCode($hash)
    {
        $signature = SignatureNumerique::where('hash_unique', $hash)->firstOrFail();
        
        $qrData = route('admin.signatures.verifier', $hash);
        
        return response()->json([
            'qr_data' => $qrData,
            'hash' => $hash,
            'document' => $signature->document_type . ' - ' . $signature->document_id
        ]);
    }

    /**
     * API: Sauvegarder une signature (AJAX)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'document_type' => 'required|string',
            'document_id' => 'required|integer',
            'signataire_type' => 'required|string',
            'signataire_id' => 'required|integer',
            'signature_data' => 'required|string',
            'commentaire' => 'nullable|string'
        ]);
        
        $signataire = $validated['signataire_type']::find($validated['signataire_id']);
        
        if (!$signataire) {
            return response()->json(['error' => 'Signataire introuvable'], 404);
        }
        
        $signature = $this->signatureService->sauvegarder(
            $validated['document_type'],
            $validated['document_id'],
            $signataire,
            $validated['signature_data'],
            $validated['commentaire'] ?? null
        );
        
        return response()->json([
            'success' => true,
            'signature' => $signature,
            'verification_url' => route('admin.signatures.verifier', $signature->hash_unique)
        ]);
    }

    /**
     * Récupérer les signatures d'un document
     */
    public function getSignatures($documentType, $documentId)
    {
        $signatures = $this->signatureService->getSignatures($documentType, $documentId);
        
        return response()->json($signatures);
    }
}