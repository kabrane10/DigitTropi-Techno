@extends('layouts.admin')

@section('title', 'Détails producteur')
@section('header', 'Fiche producteur')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Carte informations -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="text-center mb-4">
                <div class="w-24 h-24 bg-primary/20 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-user-farmer text-primary text-4xl"></i>
                </div>
                <h3 class="text-xl font-bold">{{ $producteur->nom_complet }}</h3>
                <p class="text-gray-500">{{ $producteur->code_producteur }}</p>
            </div>
            
            <div class="space-y-3">
                <div class="flex items-center">
                    <i class="fas fa-phone w-8 text-gray-400"></i>
                    <span>{{ $producteur->contact }}</span>
                </div>
                <div class="flex items-center">
                    <i class="fas fa-envelope w-8 text-gray-400"></i>
                    <span>{{ $producteur->email ?? 'Non renseigné' }}</span>
                </div>
                <div class="flex items-center">
                    <i class="fas fa-map-marker-alt w-8 text-gray-400"></i>
                    <span>{{ $producteur->localisation }} ({{ $producteur->region }})</span>
                </div>
                <div class="flex items-center">
                    <i class="fas fa-tractor w-8 text-gray-400"></i>
                    <span>{{ $producteur->culture_pratiquee }}</span>
                </div>
                <div class="flex items-center">
                    <i class="fas fa-chart-line w-8 text-gray-400"></i>
                    <span>{{ number_format($producteur->superficie_totale, 2) }} hectares</span>
                </div>
                <div class="flex items-center">
                    <i class="fas fa-users w-8 text-gray-400"></i>
                    <span>{{ $producteur->cooperative->nom ?? 'Indépendant' }}</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Statistiques -->
    <div class="lg:col-span-2 space-y-6">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-blue-500 rounded-xl p-4 text-white">
                <p class="text-sm opacity-90">Crédits</p>
                <p class="text-2xl font-bold">{{ $stats['nb_credits'] }}</p>
            </div>
            <div class="bg-green-500 rounded-xl p-4 text-white">
                <p class="text-sm opacity-90">Montant crédits</p>
                <p class="text-2xl font-bold">{{ number_format($stats['total_credits'], 0, ',', ' ') }} CFA</p>
            </div>
            <div class="bg-yellow-500 rounded-xl p-4 text-white">
                <p class="text-sm opacity-90">Collectes</p>
                <p class="text-2xl font-bold">{{ $stats['nb_collectes'] }}</p>
            </div>
            <div class="bg-purple-500 rounded-xl p-4 text-white">
                <p class="text-sm opacity-90">Production totale</p>
                <p class="text-2xl font-bold">{{ number_format($stats['total_production']) }} kg</p>
            </div>
        </div>
        
        <!-- Crédits -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold mb-4">Crédits</h3>
            @if($producteur->credits->count() > 0)
            <div class="space-y-3">
                @foreach($producteur->credits as $credit)
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <div>
                        <p class="font-medium">{{ $credit->code_credit }}</p>
                        <p class="text-sm text-gray-500">{{ number_format($credit->montant_total, 0, ',', ' ') }} CFA</p>
                    </div>
                    <div>
                        <span class="px-2 py-1 text-xs rounded-full 
                            @if($credit->statut == 'actif') bg-yellow-100 text-yellow-800
                            @elseif($credit->statut == 'rembourse') bg-green-100 text-green-800
                            @else bg-red-100 text-red-800
                            @endif">
                            {{ $credit->statut }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <p class="text-gray-500 text-center">Aucun crédit</p>
            @endif
        </div>
        
        <!-- Collectes -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold mb-4">Dernières collectes</h3>
            @if($producteur->collectes->count() > 0)
            <div class="space-y-3">
                @foreach($producteur->collectes->take(5) as $collecte)
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <div>
                        <p class="font-medium">{{ $collecte->produit }}</p>
                        <p class="text-sm text-gray-500">{{ number_format($collecte->quantite_nette) }} kg</p>
                    </div>
                    <div>
                        <p class="text-sm">{{ number_format($collecte->montant_total, 0, ',', ' ') }} CFA</p>
                        <p class="text-xs text-gray-500">{{ $collecte->date_collecte->format('d/m/Y') }}</p>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <p class="text-gray-500 text-center">Aucune collecte</p>
            @endif
        </div>
    </div>
</div>

<!-- Résumé des crédits -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6 mt-6">
    <div class="bg-blue-50 rounded-xl p-4">
        <p class="text-sm text-gray-600">Nombre total de crédits</p>
        <p class="text-2xl font-bold text-blue-600">{{ $producteur->credits->count() }}</p>
    </div>
    <div class="bg-yellow-50 rounded-xl p-4">
        <p class="text-sm text-gray-600">Crédits actifs</p>
        <p class="text-2xl font-bold text-yellow-600">{{ $producteur->credits->where('statut', 'actif')->count() }}</p>
    </div>
    <div class="bg-green-50 rounded-xl p-4">
        <p class="text-sm text-gray-600">Crédits remboursés</p>
        <p class="text-2xl font-bold text-green-600">{{ $producteur->credits->where('statut', 'rembourse')->count() }}</p>
    </div>
    <div class="bg-red-50 rounded-xl p-4">
        <p class="text-sm text-gray-600">Montant dû</p>
        <p class="text-2xl font-bold text-red-600">{{ number_format($producteur->credits->where('statut', 'actif')->sum('montant_restant'), 0, ',', ' ') }} CFA</p>
    </div>
</div>

<!-- Section Crédits du producteur -->
<div class="bg-white rounded-xl shadow-sm p-6 mt-6">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold">Historique des crédits</h3>
        <a href="{{ route('admin.credits.create', ['producteur_id' => $producteur->id]) }}" 
           class="bg-primary text-white px-3 py-1 rounded-lg text-sm hover:bg-secondary">
            <i class="fas fa-plus mr-1"></i>Nouveau crédit
        </a>
    </div>
    
    @if($producteur->credits->count() > 0)
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left text-xs">Code</th>
                    <th class="px-4 py-2 text-left text-xs">Date</th>
                    <th class="px-4 py-2 text-right text-xs">Montant</th>
                    <th class="px-4 py-2 text-right text-xs">Reste</th>
                    <th class="px-4 py-2 text-left text-xs">Échéance</th>
                    <th class="px-4 py-2 text-left text-xs">Statut</th>
                    <th class="px-4 py-2 text-center text-xs">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($producteur->credits as $credit)
                <tr>
                    <td class="px-4 py-2 text-sm">{{ $credit->code_credit }}</td>
                    <td class="px-4 py-2 text-sm">{{ $credit->date_octroi->format('d/m/Y') }}</td>
                    <td class="px-4 py-2 text-right">{{ number_format($credit->montant_total, 0, ',', ' ') }} CFA</td>
                    <td class="px-4 py-2 text-right {{ $credit->montant_restant > 0 ? 'text-red-600' : 'text-green-600' }}">
                        {{ number_format($credit->montant_restant, 0, ',', ' ') }} CFA
                    </td>
                    <td class="px-4 py-2 text-sm">{{ $credit->date_echeance->format('d/m/Y') }}</td>
                    <td class="px-4 py-2">
                        <span class="px-2 py-1 text-xs rounded-full 
                            @if($credit->statut == 'actif') bg-yellow-100 text-yellow-800
                            @elseif($credit->statut == 'rembourse') bg-green-100 text-green-800
                            @else bg-red-100 text-red-800
                            @endif">
                            {{ $credit->statut }}
                        </span>
                    </td>
                    <td class="px-4 py-2 text-center">
                        <a href="{{ route('admin.credits.show', $credit) }}" class="text-blue-600 hover:text-blue-800">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot class="bg-gray-50">
                <tr>
                    <td colspan="2" class="px-4 py-2 font-semibold">Totaux</td>
                    <td class="px-4 py-2 text-right font-semibold">{{ number_format($producteur->credits->sum('montant_total'), 0, ',', ' ') }} CFA</td>
                    <td class="px-4 py-2 text-right font-semibold text-red-600">{{ number_format($producteur->credits->where('statut', 'actif')->sum('montant_restant'), 0, ',', ' ') }} CFA</td>
                    <td colspan="3"></td>
                </tr>
            </tfoot>
        </table>
    </div>
    @else
    <div class="text-center py-6 text-gray-500">
        <i class="fas fa-hand-holding-usd text-4xl mb-2 opacity-50"></i>
        <p>Aucun crédit pour ce producteur</p>
        <a href="{{ route('admin.credits.create', ['producteur_id' => $producteur->id]) }}" class="inline-block mt-2 text-primary hover:underline">
            Accorder un premier crédit
        </a>
    </div>
    @endif
</div>


@endsection
