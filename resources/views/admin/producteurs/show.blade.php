@extends('layouts.admin')

@section('title', 'Détails producteur')
@section('header', 'Fiche producteur')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-1 space-y-6">
        <div class="bg-white rounded-xl shadow-sm p-6 border-t-4 border-primary">
            <div class="text-center mb-6">
                <div class="relative inline-block">
                    <div class="w-24 h-24 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-user-farmer text-primary text-4xl"></i>
                    </div>
                    <span class="absolute bottom-4 right-0 w-5 h-5 rounded-full border-2 border-white {{ $producteur->statut == 'actif' ? 'bg-green-500' : 'bg-red-500' }}"></span>
                </div>
                <h3 class="text-xl font-bold text-gray-800">{{ $producteur->nom_complet }}</h3>
                <p class="text-primary font-mono text-sm font-semibold">{{ $producteur->code_producteur }}</p>
                <div class="mt-2">
                    <span class="px-3 py-1 text-xs font-bold uppercase rounded-full {{ $producteur->statut == 'actif' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        {{ $producteur->statut }}
                    </span>
                </div>
            </div>
            
            <div class="space-y-4 border-t pt-4">
                <div class="flex items-start">
                    <i class="fas fa-phone w-8 text-gray-400 mt-1"></i>
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-bold">Contact</p>
                        <p class="text-sm">{{ $producteur->contact }}</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <i class="fas fa-envelope w-8 text-gray-400 mt-1"></i>
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-bold">Email</p>
                        <p class="text-sm text-blue-600 italic">{{ $producteur->email ?? 'Non renseigné' }}</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <i class="fas fa-map-marked-alt w-8 text-gray-400 mt-1"></i>
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-bold">Localisation</p>
                        <p class="text-sm">{{ $producteur->region ?? 'Non renseigné' }}, {{ $producteur->commune ?? 'Non renseigné' }}</p>
                        @if($producteur->latitude && $producteur->longitude)
                            <a href="https://www.google.com/maps?q={{ $producteur->latitude }},{{ $producteur->longitude }}" 
                               target="_blank" class="text-xs text-primary hover:underline mt-1 inline-block">
                                <i class="fas fa-external-link-alt mr-1"></i>Voir sur Google Maps
                            </a>
                        @endif
                    </div>
                </div>
                <div class="flex items-start">
                    <i class="fas fa-tractor w-8 text-gray-400 mt-1"></i>
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-bold">Position exacte</p>
                        <p class="text-sm">{{ $producteur->localisation ?? 'Non renseigné' }} </p>
                        @if($producteur->latitude && $producteur->longitude)
                            <p id="geo-location-name" class="text-xs text-gray-600 italic mt-1">
                                <i class="fas fa-spinner fa-spin mr-1"></i> Recherche du lieu...
                            </p>

                            <script>
                                fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat={{ $producteur->latitude }}&lon={{ $producteur->longitude }}`)
                                    .then(response => response.json())
                                    .then(data => {
                                        const displayName = data.display_name || "Lieu inconnu";
                                        document.getElementById('geo-location-name').innerHTML = `<i class="fas fa-location-dot text-red-500 mr-1"></i> ${displayName}`;
                                    })
                                    .catch(err => {
                                        document.getElementById('geo-location-name').innerText = "Position GPS enregistrée";
                                    });
                            </script>
                        @endif
                    </div>    
                </div>
                <div class="flex items-start">
                    <i class="fas fa-tractor w-8 text-gray-400 mt-1"></i>
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-bold">Culture & Surface</p>
                        <p class="text-sm">{{ $producteur->culture_pratiquee }} ({{ number_format($producteur->superficie_totale, 2) }} ha)</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <i class="fas fa-users w-8 text-gray-400 mt-1"></i>
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-bold">Organisation</p>
                        <p class="text-sm font-semibold text-gray-700">{{ $producteur->cooperative->nom ?? 'Indépendant' }}</p>
                    </div>
                </div>
            </div>

            <div class="mt-8 grid grid-cols-2 gap-3">
                <a href="{{ route('admin.producteurs.edit', $producteur) }}" class="flex justify-center items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                    <i class="fas fa-edit mr-2"></i> Modifier
                </a>
                <a href="{{ route('admin.producteurs.index') }}" class="flex justify-center items-center px-4 py-2 border border-gray-200 text-gray-600 rounded-lg hover:bg-gray-50 transition">
                    Retour
                </a>
            </div>
        </div>

        {{-- Widget Agent Assigné --}}
        <div class="bg-blue-600 rounded-xl shadow-sm p-5 text-white">
            <h4 class="text-sm font-bold uppercase opacity-80 mb-3">Agent de terrain</h4>
            <div class="flex items-center">
                <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-user-tie"></i>
                </div>
                <div>
                    <p class="font-bold">{{ $producteur->agent->nom_complet ?? 'Non assigné' }}</p>
                    <p class="text-xs opacity-80">Suivi technique</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="lg:col-span-2 space-y-6">
        {{-- Grille de Statistiques --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white p-4 rounded-xl shadow-sm border-b-4 border-blue-500">
                <p class="text-xs text-gray-500 font-bold uppercase">Crédits</p>
                <p class="text-xl font-black text-gray-800">{{ $stats['nb_credits'] }}</p>
            </div>
            <div class="bg-white p-4 rounded-xl shadow-sm border-b-4 border-green-500">
                <p class="text-xs text-gray-500 font-bold uppercase">Total Crédits</p>
                <p class="text-xl font-black text-gray-800">{{ number_format($stats['total_credits'], 0, ',', ' ') }} <span class="text-xs">CFA</span></p>
            </div>
            <div class="bg-white p-4 rounded-xl shadow-sm border-b-4 border-yellow-500">
                <p class="text-xs text-gray-500 font-bold uppercase">Collectes</p>
                <p class="text-xl font-black text-gray-800">{{ $stats['nb_collectes'] }}</p>
            </div>
            <div class="bg-white p-4 rounded-xl shadow-sm border-b-4 border-purple-500">
                <p class="text-xs text-gray-500 font-bold uppercase">Production</p>
                <p class="text-xl font-black text-gray-800">{{ number_format($stats['total_production']) }} <span class="text-xs">kg</span></p>
            </div>
        </div>
        
        {{-- Tabs ou Sections détaillées --}}
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="border-b px-6 py-4 flex justify-between items-center bg-gray-50/50">
                <h3 class="font-bold text-gray-700 flex items-center">
                    <i class="fas fa-hand-holding-usd mr-2 text-primary"></i> Situation Financière
                </h3>
                <a href="{{ route('admin.credits.create', ['producteur_id' => $producteur->id]) }}" 
                   class="text-xs bg-primary text-white px-3 py-1.5 rounded-lg hover:bg-secondary">
                    <i class="fas fa-plus mr-1"></i> Nouveau Crédit
                </a>
            </div>
            
            <div class="p-6">
                @if($producteur->credits->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-xs uppercase text-gray-400 border-b">
                                <th class="pb-3 font-bold">Code</th>
                                <th class="pb-3 font-bold text-right">Montant</th>
                                <th class="pb-3 font-bold text-right text-red-500">Reste</th>
                                  <th class="px-4 py-2 text-left text-xs">Échéance</th>
                                <th class="pb-3 font-bold text-center">Statut</th>
                                <th class="pb-3 font-bold text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @foreach($producteur->credits as $credit)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="py-4 font-mono text-xs">{{ $credit->code_credit }}</td>
                                <td class="py-4 text-right font-semibold">{{ number_format($credit->montant_total, 0, ',', ' ') }}</td>
                                <td class="py-4 text-right font-bold text-red-600">{{ number_format($credit->montant_restant, 0, ',', ' ') }}</td>
                                <td class="px-4 py-2 text-sm">{{ $credit->date_echeance->format('d/m/Y') }}</td>
                                <td class="py-4 text-center">
                                    <span class="px-2 py-1 text-[10px] font-bold rounded-full 
                                        @if($credit->statut == 'actif') bg-yellow-100 text-yellow-700
                                        @elseif($credit->statut == 'rembourse') bg-green-100 text-green-700
                                        @else bg-red-100 text-red-700
                                        @endif">
                                        {{ strtoupper($credit->statut) }}
                                    </span>
                                </td>
                                <td class="py-4 text-right">
                                    <a href="{{ route('admin.credits.show', $credit) }}" class="text-primary hover:text-secondary p-2">
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-10">
                    <img src="https://cdn-icons-png.flaticon.com/512/4076/4076432.png" class="w-16 h-16 mx-auto opacity-20 mb-3" alt="">
                    <p class="text-gray-400">Aucun historique financier disponible.</p>
                </div>
                @endif
            </div>
        </div>

        {{-- Dernières Collectes --}}
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-bold text-gray-700 mb-4 flex items-center text-lg">
                <i class="fas fa-box-open mr-2 text-yellow-500"></i> Dernières livraisons
            </h3>
            @forelse($producteur->collectes->take(3) as $collecte)
                <div class="flex items-center justify-between p-4 mb-3 bg-gray-50 rounded-xl hover:shadow-md transition">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-sm text-yellow-600 mr-3">
                            <i class="fas fa-leaf text-sm"></i>
                        </div>
                        <div>
                            <p class="font-bold text-gray-800 text-sm">{{ $collecte->produit }}</p>
                            <p class="text-xs text-gray-500">{{ $collecte->date_collecte->translatedFormat('d F Y') }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-black text-gray-800">{{ number_format($collecte->quantite_nette) }} kg</p>
                        <p class="text-xs text-green-600 font-bold">{{ number_format($collecte->montant_total, 0, ',', ' ') }} CFA</p>
                    </div>
                </div>
            @empty
                <p class="text-center text-gray-400 py-4 italic">Aucune collecte enregistrée.</p>
            @endforelse
        </div>
    </div>
</div>

{{-- Notes additionnelles --}}
@if($producteur->notes)
<div class="mt-6 bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-r-xl">
    <div class="flex">
        <i class="fas fa-sticky-note text-yellow-400 mt-1 mr-3"></i>
        <div>
            <h4 class="text-sm font-bold text-yellow-800 uppercase">Notes & Observations</h4>
            <p class="text-sm text-yellow-700 mt-1">{{ $producteur->notes }}</p>
        </div>
    </div>
</div>
@endif
@endsection