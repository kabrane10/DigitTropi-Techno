<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CreditAgricole;
use App\Models\Producteur;
use App\Models\Collecte;
use App\Models\DistributionSemence;
use\App\Models\DistributionIntrant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SignatureController extends Controller
{
    public function save(Request $request)
    {
        $validated = $request->validate([
            'document_type' => 'required|string',
            'document_id' => 'required|integer',
            'signataire_type' => 'required|string',
            'signature_data' => 'required|string'
        ]);
        
        $model = $this->getDocumentModel($validated['document_type']);
        $document = $model::findOrFail($validated['document_id']);
        
        // Enregistrer la signature
        $document->sign(
            $validated['signataire_type'],
            Auth::guard('admin')->id(),
            Auth::guard('admin')->user()->nom,
            $validated['signature_data']
        );
        
        return response()->json(['success' => true]);
    }
    
    private function getDocumentModel($type)
    {
        $models = [
            'credit' => CreditAgricole::class,
            'collecte' => Collecte::class,
            'distribution' => DistributionSemence::class,
            'producteur' => Producteur::class,
            'cooperative' => Cooperative::class,

        ];
        
        return $models[$type] ?? CreditAgricole::class;
    }
    
    public function get($id, $type)
    {
        $model = $this->getDocumentModel($type);
        $document = $model::findOrFail($id);
        
        $signatures = [
            'producteur' => $document->getSignatureFrom('producteur'),
            'agent' => $document->getSignatureFrom('agent'),
            'cooperative' => $document->getSignatureFrom('cooperative')
        ];
        
        return response()->json($signatures);
    }
}