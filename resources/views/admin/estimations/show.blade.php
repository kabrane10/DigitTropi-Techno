@extends('layouts.admin')

@section('title', 'Détails estimation')
@section('header', 'Fiche d\'estimation de besoin')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="bg-gradient-to-r from-primary to-secondary px-6 py-4">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-white text-xl font-semibold">Estimation #{{ $estimation->code_estimation }}</h3>
                    <p class="text-white/80 text-sm">{{ $estimation->date_estimation->format('d/m/Y') }}</p>
                </div>
                <div class="flex gap-2">
                    <span class="px-3 py-1 bg-white/20 rounded-full text-white text-sm">
                        {{ $estimation->statut_label }}
                    </span>
                    <a href="{{ route('admin.estimations.print', $estimation->id) }}" target="_blank"  class="px-3 py-1 bg-white/20 rounded-full text-white text-sm hover:bg-white/30">
                        <i class="fas fa-print mr-1"></i>Imprimer 
                    </a>
                </div>
            </div>
        </div>
        
        <div class="p-6">
            <!-- Producteur -->
            <div class="mb-6">
                <h4 class="text-lg font-semibold mb-3">Producteur</h4>
                <div class="grid grid-cols-2 gap-4 p-4 bg-gray-50 rounded-lg">
                    <div><label class="text-gray-500 text-sm">Nom complet</label><p class="font-semibold">{{ $estimation->producteur->nom_complet }}</p></div>
                    <div><label class="text-gray-500 text-sm">Code producteur</label><p>{{ $estimation->producteur->code_producteur }}</p></div>
                    <div><label class="text-gray-500 text-sm">Contact</label><p>{{ $estimation->producteur->contact }}</p></div>
                    <div><label class="text-gray-500 text-sm">Région</label><p>{{ $estimation->producteur->region }}</p></div>
                </div>
            </div>
            
            <!-- Besoins -->
            <div class="mb-6">
                <h4 class="text-lg font-semibold mb-3">Besoins estimés</h4>
                <div class="grid grid-cols-2 gap-4 p-4 bg-gray-50 rounded-lg">
                    <div><label class="text-gray-500 text-sm">Semence</label><p class="font-semibold">{{ $estimation->semence->nom }} ({{ $estimation->semence->variete }})</p></div>
                    <div><label class="text-gray-500 text-sm">Quantité estimée</label><p class="text-primary font-bold">{{ number_format($estimation->quantite_estimee) }} {{ $estimation->semence->unite }}</p></div>
                    <div><label class="text-gray-500 text-sm">Superficie prévue</label><p>{{ number_format($estimation->superficie_prevue, 2) }} hectares</p></div>
                </div>
            </div>

            <!-- Récapitulatif Financier -->
            <div class="mb-6">
                <h4 class="text-lg font-semibold mb-3">Récapitulatif Financier</h4>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4 p-4 bg-blue-50 rounded-lg">
                    <div><label class="text-gray-500 text-sm">Coût semences</label><p class="font-semibold">{{ number_format($estimation->cout_semences ?? 0, 0, ',', ' ') }} CFA</p></div>
                    <div><label class="text-gray-500 text-sm">Coût intrants</label><p class="font-semibold">{{ number_format($estimation->cout_intrants ?? 0, 0, ',', ' ') }} CFA</p></div>
                    <div><label class="text-gray-500 text-sm">Autres frais</label><p class="font-semibold">{{ number_format($estimation->autres_frais ?? 0, 0, ',', ' ') }} CFA</p></div>
                    <div class="col-span-2 md:col-span-3 mt-2 pt-2 border-t">
                        <label class="text-gray-600 text-sm">Montant Crédit Estimé</label>
                        <p class="font-bold text-green-600 text-lg">{{ number_format($estimation->credit_montant ?? 0, 0, ',', ' ') }} CFA</p>
                    </div>
                    <div class="col-span-2 md:col-span-3 font-bold text-right bg-white p-3 rounded-md">
                        <label class="text-gray-800">Total de l'estimation</label>
                        <p class="text-primary text-xl">{{ number_format($estimation->total_estimation ?? 0, 0, ',', ' ') }} CFA</p>
                    </div>
                </div>
            </div>

            <!-- Intrants supplémentaires -->
            @if($estimation->intrants && count(json_decode($estimation->intrants, true)) > 0)
            <div class="mb-6">
                <h4 class="text-lg font-semibold mb-3">Détails des Intrants</h4>
                <div class="p-4 bg-gray-50 rounded-lg">
                    <table class="w-full">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-3 py-2 text-left">Type</th>
                                <th class="px-3 py-2 text-right">Quantité</th>
                                <th class="px-3 py-2 text-left">Unité</th>
                                <th class="px-3 py-2 text-right">Coût</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(json_decode($estimation->intrants, true) as $intrant)
                            <tr>
                                <td class="px-3 py-2">{{ ucfirst($intrant['type'] ?? 'N/A') }}</td>
                                <td class="px-3 py-2 text-right">{{ number_format($intrant['quantite'] ?? 0, 2) }}</td>
                                <td class="px-3 py-2">{{ $intrant['unite'] ?? 'N/A' }}</td>
                                <td class="px-3 py-2 text-right">{{ number_format($intrant['cout_estime'] ?? 0, 0, ',', ' ') }} CFA</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
            
            <!-- Observations -->
            @if($estimation->observations)
            <div class="mb-6"><label class="text-gray-500 text-sm">Observations</label><p class="mt-1 p-3 bg-gray-50 rounded-lg">{{ $estimation->observations }}</p></div>
            @endif
            
            <!-- Actions -->
            <div class="mt-6 pt-6 border-t flex justify-between">
                <a href="{{ route('admin.estimations.index') }}" class="text-gray-600 hover:text-gray-800"><i class="fas fa-arrow-left mr-1"></i>Retour</a>
                <div class="space-x-3">
                    @if($estimation->statut == 'en_attente')
                    <form action="{{ route('admin.estimations.convert-to-credit', $estimation) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600">
                            <i class="fas fa-exchange-alt mr-2"></i>Convertir en crédit
                        </button>
                    </form>
                    @endif
                    <a href="{{ route('admin.estimations.edit', $estimation) }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                        <i class="fas fa-edit mr-2"></i>Modifier
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media print{
    .no-print{
        display:none
        }
        .bg-gradient-to-r{
            -webkit-print-color-adjust:exact;
            print-color-adjust:exact
        }
    }
</style>
@endsection
