<?php
// app/Traits/SignatureTrait.php

namespace App\Traits;

use App\Services\SignatureService;

trait SignatureTrait
{
    /**
     * Configurer les signatures pour une vue
     * 
     * @param string $documentType (credit, estimation, collecte, distribution_semence, distribution_intrant, bordereau)
     * @param mixed $document Le document (crédit, collecte, etc.)
     * @param array $signatairesConfig Configuration des signataires requis
     * @return array
     */
    protected function configureSignatures($documentType, $document, $signatairesConfig = [])
    {
        $signatureService = app(SignatureService::class);
        
        // Configuration par défaut selon le type de document
        $defaultConfig = $this->getDefaultSignatureConfig($documentType, $document);
        
        // Fusionner avec la configuration personnalisée si fournie
        $signatures_config = !empty($signatairesConfig) ? $signatairesConfig : $defaultConfig;
        
        // Récupérer les signatures existantes
        $signatures_existantes = [];
        foreach ($signatures_config as $config) {
            $type = $config['type'];
            $signataireType = $config['signataire_type'] ?? null;
            
            if ($signataireType) {
                $signature = $signatureService->getSignatures($documentType, $document->id)
                    ->where('signataire_type', $signataireType)
                    ->first();
                    
                if ($signature) {
                    $signatures_existantes[$type] = [
                        'signature_data' => $signature->signature_data,
                        'signed_at' => $signature->signed_at->format('d/m/Y H:i:s'),
                        'signataire_nom' => $signature->signataire_nom,
                        'hash' => $signature->hash_unique
                    ];
                }
            }
        }
        
        return [
            'signatures_config' => $signatures_config,
            'signatures_existantes' => $signatures_existantes
        ];
    }
    
    /**
     * Configuration par défaut selon le type de document
     */
    private function getDefaultSignatureConfig($documentType, $document)
    {
        $configs = [
            'credit' => [
                [
                    'type' => 'beneficiaire',
                    'label' => $document->cooperative_id ? 'Signature de la coopérative' : 'Signature du producteur',
                    'signataire_type' => $document->cooperative_id ? 'App\\Models\\Cooperative' : 'App\\Models\\Producteur',
                    'required' => true
                ],
                [
                    'type' => 'agent',
                    'label' => 'Signature de l\'agent Tropi-Techno',
                    'signataire_type' => 'App\\Models\\Admin',
                    'required' => true
                ]
            ],
            
            'estimation' => [
                [
                    'type' => 'beneficiaire',
                    'label' => 'Signature du producteur',
                    'signataire_type' => 'App\\Models\\Producteur',
                    'required' => true
                ],
                [
                    'type' => 'agent',
                    'label' => 'Signature de l\'agent',
                    'signataire_type' => 'App\\Models\\Admin',
                    'required' => true
                ]
            ],
            
            'collecte' => [
                [
                    'type' => 'beneficiaire',
                    'label' => $document->cooperative_id ? 'Signature de la coopérative' : 'Signature du producteur',
                    'signataire_type' => $document->cooperative_id ? 'App\\Models\\Cooperative' : 'App\\Models\\Producteur',
                    'required' => true
                ],
                [
                    'type' => 'agent',
                    'label' => 'Signature de l\'agent collecteur',
                    'signataire_type' => 'App\\Models\\Admin',
                    'required' => true
                ]
            ],
            
            'distribution_semence' => [
                [
                    'type' => 'beneficiaire',
                    'label' => $document->cooperative_id ? 'Signature de la coopérative' : 'Signature du producteur',
                    'signataire_type' => $document->cooperative_id ? 'App\\Models\\Cooperative' : 'App\\Models\\Producteur',
                    'required' => true
                ],
                [
                    'type' => 'agent',
                    'label' => 'Signature de l\'agent distributeur',
                    'signataire_type' => 'App\\Models\\Admin',
                    'required' => true
                ]
            ],
            
            'distribution_intrant' => [
                [
                    'type' => 'beneficiaire',
                    'label' => $document->cooperative_id ? 'Signature de la coopérative' : 'Signature du producteur',
                    'signataire_type' => $document->cooperative_id ? 'App\\Models\\Cooperative' : 'App\\Models\\Producteur',
                    'required' => true
                ],
                [
                    'type' => 'agent',
                    'label' => 'Signature de l\'agent distributeur',
                    'signataire_type' => 'App\\Models\\Admin',
                    'required' => true
                ]
            ],
            
            'bordereau' => [
                [
                    'type' => 'beneficiaire',
                    'label' => 'Signature du bénéficiaire',
                    'signataire_type' => null,
                    'required' => true
                ],
                [
                    'type' => 'agent',
                    'label' => 'Signature de l\'agent Tropi-Techno',
                    'signataire_type' => 'App\\Models\\Admin',
                    'required' => true
                ]
            ]
        ];
        
        return $configs[$documentType] ?? $configs['bordereau'];
    }
    
    /**
     * Sauvegarder les signatures après soumission du formulaire
     */
    protected function saveSignatures($request, $documentType, $document, $signatairesConfig = null)
    {
        $signatureService = app(SignatureService::class);
        $saved = [];
        
        // Si pas de configuration, utiliser celle par défaut
        if (!$signatairesConfig) {
            $signatairesConfig = $this->getDefaultSignatureConfig($documentType, $document);
        }
        
        foreach ($signatairesConfig as $config) {
            $type = $config['type'];
            $signatureData = $request->input("signature_{$type}");
            $commentaire = $request->input("signature_{$type}_commentaire");
            
            if ($signatureData && isset($config['signataire_type'])) {
                $signataire = null;
                
                if ($config['signataire_type'] === 'App\\Models\\Producteur' && $document->producteur_id) {
                    $signataire = $document->producteur;
                } elseif ($config['signataire_type'] === 'App\\Models\\Cooperative' && $document->cooperative_id) {
                    $signataire = $document->cooperative;
                } elseif ($config['signataire_type'] === 'App\\Models\\Admin') {
                    $signataire = auth()->guard('admin')->user();
                }
                
                if ($signataire) {
                    $signature = $signatureService->sauvegarder(
                        $documentType,
                        $document->id,
                        $signataire,
                        $signatureData,
                        $commentaire
                    );
                    $saved[$type] = $signature;
                }
            }
        }
        
        return $saved;
    }
}