@extends('layouts.admin')

@section('title', 'Détails distribution')
@section('header', 'Fiche de distribution de semences')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <!-- En-tête -->
        <div class="bg-gradient-to-r from-primary to-secondary px-6 py-4">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-white text-xl font-semibold">Distribution #{{ $distribution->code_distribution }}</h3>
                    <p class="text-white/80 text-sm">{{ $distribution->date_distribution->format('d/m/Y') }}</p>
                </div>
                <div class="flex gap-2">
                    <span class="px-3 py-1 bg-white/20 rounded-full text-white text-sm">
                        <i class="fas fa-cloud-sun mr-1"></i> {{ $distribution->saison }}
                    </span>
                    <button onclick="window.print()" class="px-3 py-1 bg-white/20 rounded-full text-white text-sm hover:bg-white/30 transition">
                        <i class="fas fa-print mr-1"></i> Imprimer
                    </button>
                </div>
            </div>
        </div>
        
        <div class="p-6" id="print-content">
            
            @php
                // Déterminer le type de bénéficiaire
                $isCooperative = ($distribution->cooperative_id || $distribution->beneficiaire_type === 'App\\Models\\Cooperative');
                $beneficiaire = $isCooperative ? $distribution->cooperative : $distribution->producteur;
            @endphp
            
            <!-- Infos Bénéficiaire (Producteur ou Coopérative) -->
            <div class="mb-6">
                <h4 class="text-lg font-semibold text-dark mb-3">
                    @if($isCooperative)
                        <i class="fas fa-handshake text-purple-600 mr-2"></i>Coopérative bénéficiaire
                    @else
                        <i class="fas fa-user text-primary mr-2"></i>Producteur
                    @endif
                </h4>
                <div class="grid grid-cols-2 gap-4 p-4 rounded-lg {{ $isCooperative ? 'bg-purple-50' : 'bg-gray-50' }}">
                    @if($isCooperative)
                        {{-- Affichage Coopérative --}}
                        <div>
                            <label class="text-gray-500 text-sm">Nom de la coopérative</label>
                            <p class="font-semibold text-purple-800">{{ $beneficiaire->nom ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="text-gray-500 text-sm">Code coopérative</label>
                            <p>{{ $beneficiaire->code_cooperative ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="text-gray-500 text-sm">Contact</label>
                            <p>{{ $beneficiaire->contact ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="text-gray-500 text-sm">Région</label>
                            <p>{{ $beneficiaire->region ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="text-gray-500 text-sm">Localisation</label>
                            <p>{{ $beneficiaire->localisation ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="text-gray-500 text-sm">Nombre de membres</label>
                            <p>{{ number_format($beneficiaire->nombre_membres ?? 0) }} producteurs</p>
                        </div>
                    @else
                        {{-- Affichage Producteur Individuel --}}
                        <div>
                            <label class="text-gray-500 text-sm">Nom complet</label>
                            <p class="font-semibold">{{ $beneficiaire->nom_complet ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="text-gray-500 text-sm">Code producteur</label>
                            <p>{{ $beneficiaire->code_producteur ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="text-gray-500 text-sm">Contact</label>
                            <p>{{ $beneficiaire->contact ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="text-gray-500 text-sm">Région</label>
                            <p>{{ $beneficiaire->region ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="text-gray-500 text-sm">Localisation</label>
                            <p>{{ $beneficiaire->localisation ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="text-gray-500 text-sm">Culture pratiquée</label>
                            <p>{{ $beneficiaire->culture_pratiquee ?? 'N/A' }}</p>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Infos semences -->
            <div class="mb-6">
                <h4 class="text-lg font-semibold text-dark mb-3">
                    <i class="fas fa-seedling text-primary mr-2"></i>Semences distribuées
                </h4>
                <div class="grid grid-cols-2 gap-4 p-4 bg-gray-50 rounded-lg">
                    <div>
                        <label class="text-gray-500 text-sm">Semence</label>
                        <p class="font-semibold">{{ $distribution->semence->nom ?? 'N/A' }} ({{ $distribution->semence->variete ?? 'N/A' }})</p>
                    </div>
                    <div>
                        <label class="text-gray-500 text-sm">Quantité</label>
                        <p class="font-semibold text-primary">{{ number_format($distribution->quantite) }} {{ $distribution->semence->unite ?? 'kg' }}</p>
                    </div>
                    <div>
                        <label class="text-gray-500 text-sm">Prix unitaire</label>
                        <p class="font-semibold text-blue-600">{{ number_format($distribution->prix_unitaire ?? $distribution->semence->prix_unitaire ?? 0, 0, ',', ' ') }} CFA / {{ $distribution->semence->unite ?? 'kg' }}</p>
                    </div>
                    <div>
                        <label class="text-gray-500 text-sm">Montant total</label>
                        <p class="font-semibold text-green-600">{{ number_format($distribution->montant_total ?? ($distribution->quantite * ($distribution->prix_unitaire ?? $distribution->semence->prix_unitaire ?? 0)), 0, ',', ' ') }} CFA</p>
                    </div>
                    <div>
                        <label class="text-gray-500 text-sm">Superficie emblavée</label>
                        <p>{{ number_format($distribution->superficie_emblevee, 2) }} hectares</p>
                    </div>
                    <div>
                        <label class="text-gray-500 text-sm">Rendement estimé</label>
                        <p>
                            @if($distribution->rendement_estime)
                                <span class="font-semibold text-blue-600">{{ number_format($distribution->rendement_estime) }} kg/ha</span>
                            @else
                                <span class="text-gray-400">Non estimé</span>
                            @endif
                        </p>
                    </div>
                </div>
                
                <!-- Production totale estimée -->
                <div class="mt-3 p-3 bg-green-50 rounded-lg">
                    <div class="flex justify-between items-center">
                        <p class="text-sm font-semibold text-dark">
                            <i class="fas fa-chart-line text-primary mr-1"></i>Production totale estimée :
                        </p>
                        <p class="text-lg font-bold text-primary">
                            @php
                                $productionTotale = ($distribution->superficie_emblevee ?? 0) * ($distribution->rendement_estime ?? 0);
                            @endphp
                            {{ number_format($productionTotale) }} kg
                        </p>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Superficie × Rendement estimé</p>
                </div>
            </div>
            
            <!-- Crédit associé -->
            @if($distribution->credit)
            <div class="mb-6">
                <h4 class="text-lg font-semibold text-dark mb-3">
                    <i class="fas fa-hand-holding-usd text-primary mr-2"></i>Crédit associé
                </h4>
                <div class="p-4 bg-blue-50 rounded-lg">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-gray-500 text-sm">Code crédit</label>
                            <p class="font-mono">{{ $distribution->credit->code_credit }}</p>
                        </div>
                        <div>
                            <label class="text-gray-500 text-sm">Bénéficiaire du crédit</label>
                            <p>
                                @if($distribution->credit->cooperative_id)
                                    <span class="text-purple-600"><i class="fas fa-handshake mr-1"></i> {{ $distribution->credit->cooperative->nom ?? 'N/A' }}</span>
                                @else
                                    <span class="text-green-600"><i class="fas fa-user mr-1"></i> {{ $distribution->credit->producteur->nom_complet ?? 'N/A' }}</span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <label class="text-gray-500 text-sm">Montant total</label>
                            <p>{{ number_format($distribution->credit->montant_total, 0, ',', ' ') }} CFA</p>
                        </div>
                        <div>
                            <label class="text-gray-500 text-sm">Reste à payer</label>
                            <p class="text-orange-600">{{ number_format($distribution->credit->montant_restant, 0, ',', ' ') }} CFA</p>
                        </div>
                        <div>
                            <label class="text-gray-500 text-sm">Statut</label>
                            <span class="px-2 py-1 text-xs rounded-full 
                                @if($distribution->credit->statut == 'actif') bg-yellow-100 text-yellow-800
                                @elseif($distribution->credit->statut == 'rembourse') bg-green-100 text-green-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ $distribution->credit->statut }}
                            </span>
                        </div>
                        <div>
                            <label class="text-gray-500 text-sm">Date d'échéance</label>
                            <p>{{ $distribution->credit->date_echeance?->format('d/m/Y') ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            
            <!-- Observations -->
            @if($distribution->observations)
            <div class="mb-6">
                <h4 class="text-lg font-semibold text-dark mb-3">
                    <i class="fas fa-comment text-primary mr-2"></i>Observations
                </h4>
                <p class="p-3 bg-gray-50 rounded-lg">{{ $distribution->observations }}</p>
            </div>
            @endif
            
            <!-- Signatures pour impression -->
            <div class="mt-6 pt-6 border-t print-signatures">
                <div class="grid grid-cols-2 gap-8">
                    <div class="text-center">
                        <p class="text-sm text-gray-500 mb-2">
                            Signature du {{ $isCooperative ? 'représentant de la coopérative' : 'producteur' }}
                        </p>
                        <div class="border-t border-gray-300 pt-2 mt-8">
                            <p class="text-xs text-gray-400">
                                @if($isCooperative)
                                    {{ $beneficiaire->nom ?? 'N/A' }}
                                @else
                                    {{ $beneficiaire->nom_complet ?? 'N/A' }}
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="text-center">
                        <p class="text-sm text-gray-500 mb-2">Signature de l'agent</p>
                        <div class="border-t border-gray-300 pt-2 mt-8">
                            <p class="text-xs text-gray-400">Tropi-Techno Sarl</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Métadonnées -->
            <div class="mt-4 pt-4 border-t text-center text-xs text-gray-400">
                <p>Document généré le {{ now()->format('d/m/Y à H:i') }}</p>
                <p>Distribution enregistrée par {{ Auth::guard('admin')->user()->name ?? 'Administrateur' }}</p>
            </div>
            
            <!-- Actions -->
            <div class="mt-6 pt-6 border-t flex justify-between no-print">
                <a href="{{ route('admin.distributions.index') }}" class="text-gray-600 hover:text-gray-800 transition">
                    <i class="fas fa-arrow-left mr-1"></i>Retour à la liste
                </a>
                <div class="space-x-3">
                    <a href="{{ route('admin.distributions.print', $distribution->id) }}" target="_blank" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition">
                        <i class="fas fa-print mr-2"></i>Imprimer la fiche
                    </a>
                    <a href="{{ route('admin.distributions.edit', $distribution) }}" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition">
                        <i class="fas fa-edit mr-2"></i>Modifier
                    </a>
                    <form action="{{ route('admin.distributions.destroy', $distribution) }}" method="POST" class="inline delete-confirm">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition">
                            <i class="fas fa-trash mr-2"></i>Supprimer
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        .no-print {
            display: none !important;
        }
        .print-signatures {
            display: block !important;
        }
        body {
            padding: 0;
            margin: 0;
        }
        .bg-gradient-to-r {
            background: #2d6a4f !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .bg-gray-50, .bg-blue-50, .bg-green-50, .bg-purple-50 {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .text-primary {
            color: #2d6a4f !important;
        }
    }
</style>

<script>
    // Confirmation de suppression avec SweetAlert
    document.querySelectorAll('.delete-confirm').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            if (confirm('Êtes-vous sûr de vouloir supprimer cette distribution ? Cette action est irréversible.')) {
                this.submit();
            }
        });
    });
</script>
@endsection