@extends('layouts.admin')

@section('title', 'Détails distribution d\'intrants')
@section('header', 'Fiche de distribution d\'intrants')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <!-- En-tête -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-white text-xl font-semibold">
                        <i class="fas fa-flask mr-2"></i>Distribution #{{ $distribution->code_distribution }}
                    </h3>
                    <p class="text-white/80 text-sm mt-1">{{ $distribution->date_distribution->format('d/m/Y') }}</p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('admin.distributions-intrants.print', $distribution) }}" target="_blank"
                       class="px-3 py-1 bg-white/20 rounded-full text-white text-sm hover:bg-white/30 transition">
                        <i class="fas fa-print mr-1"></i> Imprimer
                    </a>
                </div>
            </div>
        </div>
        
        <div class="p-6">
            <!-- Informations générales -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Bénéficiaire -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <label class="text-gray-500 text-sm uppercase font-bold">Bénéficiaire</label>
                    @if($distribution->cooperative_id || $distribution->beneficiaire_type === 'App\\Models\\Cooperative')
                        <div class="flex items-center mt-2">
                            <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-handshake text-purple-600"></i>
                            </div>
                            <div>
                                <p class="font-bold text-lg text-purple-800">{{ $distribution->cooperative->nom ?? 'N/A' }}</p>
                                <p class="text-sm text-gray-500">Code: {{ $distribution->cooperative->code_cooperative ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4 mt-3">
                            <div>
                                <p class="text-xs text-gray-400">Contact</p>
                                <p class="text-sm font-medium">{{ $distribution->cooperative->contact ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">Région</p>
                                <p class="text-sm font-medium">{{ $distribution->cooperative->region ?? 'N/A' }}</p>
                            </div>
                        </div>
                    @else
                        <div class="flex items-center mt-2">
                            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-user text-green-600"></i>
                            </div>
                            <div>
                                <p class="font-bold text-lg text-green-800">{{ $distribution->producteur->nom_complet ?? 'N/A' }}</p>
                                <p class="text-sm text-gray-500">Code: {{ $distribution->producteur->code_producteur ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4 mt-3">
                            <div>
                                <p class="text-xs text-gray-400">Contact</p>
                                <p class="text-sm font-medium">{{ $distribution->producteur->contact ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">Région</p>
                                <p class="text-sm font-medium">{{ $distribution->producteur->region ?? 'N/A' }}</p>
                            </div>
                        </div>
                    @endif
                </div>
                
                <!-- Zone de livraison -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <label class="text-gray-500 text-sm uppercase font-bold">Zone de livraison</label>
                    <div class="flex items-center mt-2">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-map-marker-alt text-blue-600"></i>
                        </div>
                        <div>
                            <p class="font-bold text-lg text-blue-800">{{ $distribution->zone }}</p>
                            <p class="text-sm text-gray-500">Zone de distribution</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Informations intrant -->
            <div class="bg-white rounded-xl border border-gray-200 mb-6">
                <div class="border-b bg-gray-50 px-4 py-3 rounded-t-xl">
                    <h4 class="font-semibold text-gray-700">
                        <i class="fas fa-flask text-blue-600 mr-2"></i>Détails de l'intrant
                    </h4>
                </div>
                <div class="p-4">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div>
                            <p class="text-xs text-gray-400 uppercase">Intrant</p>
                            <p class="font-semibold text-lg">{{ $distribution->intrant->nom }}</p>
                            <p class="text-xs text-gray-500">{{ $distribution->intrant->type_label }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase">Quantité</p>
                            <p class="font-semibold text-lg text-primary">{{ number_format($distribution->quantite, 2) }} {{ $distribution->intrant->unite }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase">Prix unitaire</p>
                            <p class="font-semibold">{{ number_format($distribution->prix_unitaire, 0, ',', ' ') }} CFA/{{ $distribution->intrant->unite }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase">Montant total</p>
                            <p class="font-semibold text-xl text-green-600">{{ number_format($distribution->montant_total, 0, ',', ' ') }} CFA</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Informations crédit -->
            @if($distribution->credit)
            <div class="bg-white rounded-xl border border-gray-200 mb-6">
                <div class="border-b bg-gray-50 px-4 py-3 rounded-t-xl">
                    <h4 class="font-semibold text-gray-700">
                        <i class="fas fa-hand-holding-usd text-purple-600 mr-2"></i>Crédit associé
                    </h4>
                </div>
                <div class="p-4">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div>
                            <p class="text-xs text-gray-400 uppercase">Code crédit</p>
                            <p class="font-semibold">{{ $distribution->credit->code_credit }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase">Montant total</p>
                            <p class="font-semibold">{{ number_format($distribution->credit->montant_total, 0, ',', ' ') }} CFA</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase">Reste à payer</p>
                            <p class="font-semibold text-orange-600">{{ number_format($distribution->credit->montant_restant, 0, ',', ' ') }} CFA</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase">Statut</p>
                            <span class="px-2 py-1 text-xs rounded-full 
                                @if($distribution->credit->statut == 'actif') bg-yellow-100 text-yellow-700
                                @elseif($distribution->credit->statut == 'rembourse') bg-green-100 text-green-700
                                @else bg-red-100 text-red-700
                                @endif">
                                {{ strtoupper($distribution->credit->statut) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            
            <!-- Notes -->
            @if($distribution->notes)
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-r-xl mb-6">
                <div class="flex">
                    <i class="fas fa-sticky-note text-yellow-400 mt-1 mr-3"></i>
                    <div>
                        <h4 class="text-sm font-bold text-yellow-800 uppercase">Notes & Observations</h4>
                        <p class="text-sm text-yellow-700 mt-1">{{ $distribution->notes }}</p>
                    </div>
                </div>
            </div>
            @endif
            
            <!-- Actions -->
            <div class="mt-6 pt-6 border-t flex justify-between items-center">
                <a href="{{ route('admin.distributions-intrants.index') }}" class="text-gray-600 hover:text-gray-800">
                    <i class="fas fa-arrow-left mr-1"></i>Retour à la liste
                </a>
                <div class="flex space-x-3">
                    <form action="{{ route('admin.distributions-intrants.destroy', $distribution) }}" method="POST" class="inline delete-confirm">
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

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.querySelectorAll('.delete-confirm').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Confirmation',
                text: 'Êtes-vous sûr de vouloir supprimer cette distribution ? Cette action est irréversible.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Oui, supprimer',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endpush

<style>
    @media print {
        .bg-gradient-to-r {
            background: #2563eb !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .delete-confirm, .bg-red-500, .text-gray-600 {
            display: none !important;
        }
    }
</style>
@endsection