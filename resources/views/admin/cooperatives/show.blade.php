@extends('layouts.admin')

@section('title', 'Détails coopérative')
@section('header', 'Fiche coopérative')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-1 space-y-6">
        {{-- Carte principale --}}
        <div class="bg-white rounded-xl shadow-sm p-6 border-t-4 border-purple-600">
            <div class="text-center mb-6">
                <div class="relative inline-block">
                    <div class="w-24 h-24 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <span class="text-purple-600 text-3xl font-bold uppercase">
                            {{ collect(explode(' ', $cooperative->nom))->map(fn($n) => Str::limit($n, 1, ''))->join('') }}
                        </span>
                    </div>
                    <span class="absolute bottom-4 right-0 w-5 h-5 rounded-full border-2 border-white {{ $cooperative->statut == 'active' ? 'bg-green-500' : 'bg-red-500' }}"></span>
                </div>
                <h3 class="text-xl font-bold text-gray-800">{{ $cooperative->nom }}</h3>
                <p class="text-purple-600 font-mono text-sm font-semibold">{{ $cooperative->code_cooperative }}</p>
                <div class="mt-2">
                    <span class="px-3 py-1 text-xs font-bold uppercase rounded-full {{ $cooperative->statut == 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        {{ $cooperative->statut }}
                    </span>
                </div>
            </div>
            
            <div class="space-y-4 border-t pt-4">
                <div class="flex items-start">
                    <i class="fas fa-user-tie w-8 text-gray-400 mt-1"></i>
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-bold">Responsable</p>
                        <p class="text-sm font-semibold">{{ $cooperative->nom_responsable ?? 'Non renseigné' }}</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <i class="fas fa-phone w-8 text-gray-400 mt-1"></i>
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-bold">Contact</p>
                        <p class="text-sm">{{ $cooperative->contact }}</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <i class="fas fa-envelope w-8 text-gray-400 mt-1"></i>
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-bold">Email</p>
                        <p class="text-sm text-blue-600 italic">{{ $cooperative->email ?? 'Non renseigné' }}</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <i class="fas fa-map-marked-alt w-8 text-gray-400 mt-1"></i>
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-bold">Localisation</p>
                        <p class="text-sm">{{ $cooperative->region ?? 'Non renseigné' }}, {{ $cooperative->commune ?? 'Non renseigné' }}</p>
                        @if($cooperative->latitude && $cooperative->longitude)
                            <a href="https://www.google.com/maps?q={{ $cooperative->latitude }},{{ $cooperative->longitude }}" 
                               target="_blank" class="text-xs text-purple-600 hover:underline mt-1 inline-block">
                                <i class="fas fa-external-link-alt mr-1"></i>Voir sur Google Maps
                            </a>
                        @endif
                    </div>
                </div>
                <div class="flex items-start">
                    <i class="fas fa-location-dot w-8 text-gray-400 mt-1"></i>
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-bold">Adresse précise</p>
                        <p class="text-sm">{{ $cooperative->adresse ?? $cooperative->localisation ?? 'Non renseigné' }}</p>
                        @if($cooperative->latitude && $cooperative->longitude)
                            <p id="geo-location-name" class="text-xs text-gray-600 italic mt-1">
                                <i class="fas fa-spinner fa-spin mr-1"></i> Recherche du lieu...
                            </p>
                            <script>
                                fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat={{ $cooperative->latitude }}&lon={{ $cooperative->longitude }}`)
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
                    <i class="fas fa-calendar-alt w-8 text-gray-400 mt-1"></i>
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-bold">Date de création</p>
                        <p class="text-sm">{{ $cooperative->date_creation ? $cooperative->date_creation->format('d/m/Y') : 'Non renseignée' }}</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <i class="fas fa-users w-8 text-gray-400 mt-1"></i>
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-bold">Membres</p>
                        <p class="text-sm font-semibold text-purple-600">{{ number_format($cooperative->producteurs->count()) }} producteurs</p>
                    </div>
                </div>
            </div>

            <div class="mt-8 grid grid-cols-2 gap-3">
                <a href="{{ route('admin.cooperatives.edit', $cooperative) }}" class="flex justify-center items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                    <i class="fas fa-edit mr-2"></i> Modifier
                </a>
                <a href="{{ route('admin.cooperatives.index') }}" class="flex justify-center items-center px-4 py-2 border border-gray-200 text-gray-600 rounded-lg hover:bg-gray-50 transition">
                    <i class="fas fa-arrow-left mr-2"></i> Retour
                </a>
            </div>
        </div>

        {{-- Widget Gestion des opérations --}}
        <div class="bg-gradient-to-r from-purple-600 to-purple-700 rounded-xl shadow-sm p-5 text-white">
            <h4 class="text-sm font-bold uppercase opacity-80 mb-3">Gestion des opérations</h4>
            <div class="space-y-2">
                <a href="{{ route('admin.cooperatives.operations.test.dashboard', $cooperative) }}" 
                   class="flex items-center justify-between w-full px-3 py-2 bg-white/10 rounded-lg hover:bg-white/20 transition">
                    <span><i class="fas fa-chart-line mr-2"></i> Tableau de bord</span>
                    <i class="fas fa-chevron-right text-xs"></i>
                </a>
                <a href="{{ route('admin.distributions.create', ['cooperative_id' => $cooperative->id]) }}" 
                   class="flex items-center justify-between w-full px-3 py-2 bg-white/10 rounded-lg hover:bg-white/20 transition">
                    <span><i class="fas fa-seedling mr-2"></i> Distribuer semences</span>
                    <i class="fas fa-chevron-right text-xs"></i>
                </a>
                <a href="{{ route('admin.cooperatives.operations.distribution-intrant.create', $cooperative) }}" 
                   class="flex items-center justify-between w-full px-3 py-2 bg-white/10 rounded-lg hover:bg-white/20 transition">
                    <span><i class="fas fa-flask mr-2"></i> Distribuer intrants</span>
                    <i class="fas fa-chevron-right text-xs"></i>
                </a>
            <a href="{{ route('admin.collectes.create', ['cooperative_id' => $cooperative->id]) }}" 
                   class="flex items-center justify-between w-full px-3 py-2 bg-white/10 rounded-lg hover:bg-white/20 transition">
                    <span><i class="fas fa-truck mr-2"></i> Nouvelle collecte</span>
                    <i class="fas fa-chevron-right text-xs"></i>
                </a>
                <a href="{{ route('admin.credits.create', ['cooperative_id' => $cooperative->id]) }}" 
                   class="flex items-center justify-between w-full px-3 py-2 bg-white/10 rounded-lg hover:bg-white/20 transition">
                    <span><i class="fas fa-hand-holding-usd mr-2"></i> Octroyer crédit</span>
                    <i class="fas fa-chevron-right text-xs"></i>
                </a>
            </div>
        </div>
    </div>
    
    <div class="lg:col-span-2 space-y-6">
        {{-- Grille de Statistiques --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white p-4 rounded-xl shadow-sm border-b-4 border-blue-500">
                <p class="text-xs text-gray-500 font-bold uppercase">Crédits</p>
                <p class="text-xl font-black text-gray-800">{{ $stats['nb_credits'] ?? $cooperative->credits->count() }}</p>
            </div>
            <div class="bg-white p-4 rounded-xl shadow-sm border-b-4 border-green-500">
                <p class="text-xs text-gray-500 font-bold uppercase">Total Crédits</p>
                <p class="text-xl font-black text-gray-800">{{ number_format($stats['total_credits'] ?? $cooperative->credits->sum('montant_total'), 0, ',', ' ') }} <span class="text-xs">CFA</span></p>
            </div>
            <div class="bg-white p-4 rounded-xl shadow-sm border-b-4 border-yellow-500">
                <p class="text-xs text-gray-500 font-bold uppercase">Collectes</p>
                <p class="text-xl font-black text-gray-800">{{ $stats['nb_collectes'] ?? $cooperative->collectes->count() }}</p>
            </div>
            <div class="bg-white p-4 rounded-xl shadow-sm border-b-4 border-purple-500">
                <p class="text-xs text-gray-500 font-bold uppercase">Membres</p>
                <p class="text-xl font-black text-gray-800">{{ number_format($stats['nb_membres'] ?? $cooperative->producteurs->count()) }}</p>
            </div>
        </div>
        
        {{-- Section Crédits --}}
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="border-b px-6 py-4 flex justify-between items-center bg-gray-50/50">
                <h3 class="font-bold text-gray-700 flex items-center">
                    <i class="fas fa-hand-holding-usd mr-2 text-purple-600"></i> Crédits de la coopérative
                </h3>
                <a href="{{ route('admin.credits.create', ['cooperative_id' => $cooperative->id]) }}" 
                   class="text-xs bg-purple-600 text-white px-3 py-1.5 rounded-lg hover:bg-purple-700">
                    <i class="fas fa-plus mr-1"></i> Nouveau Crédit
                </a>
            </div>
            
            <div class="p-6">
                @if(($cooperative->credits ?? collect())->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-xs uppercase text-gray-400 border-b">
                                <th class="pb-3 font-bold">Code</th>
                                <th class="pb-3 font-bold text-right">Montant</th>
                                <th class="pb-3 font-bold text-right text-red-500">Reste</th>
                                <th class="pb-3 font-bold text-left">Échéance</th>
                                <th class="pb-3 font-bold text-center">Statut</th>
                                <th class="pb-3 font-bold text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @foreach($cooperative->credits as $credit)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="py-4 font-mono text-xs">{{ $credit->code_credit }}</td>
                                <td class="py-4 text-right font-semibold">{{ number_format($credit->montant_total, 0, ',', ' ') }} CFA</td>
                                <td class="py-4 text-right font-bold text-red-600">{{ number_format($credit->montant_restant, 0, ',', ' ') }} CFA</td>
                                <td class="py-4 text-left text-sm">{{ $credit->date_echeance ? $credit->date_echeance->format('d/m/Y') : 'N/A' }}</td>
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
                                    <a href="{{ route('admin.credits.show', $credit) }}" class="text-purple-600 hover:text-purple-800 p-2">
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
                    <i class="fas fa-hand-holding-usd text-5xl text-gray-300 mb-3"></i>
                    <p class="text-gray-400">Aucun crédit enregistré pour cette coopérative.</p>
                    <a href="{{  route('admin.credits.create', ['cooperative_id' => $cooperative->id]) }}" class="text-purple-600 text-sm hover:underline mt-2 inline-block">
                        <i class="fas fa-plus mr-1"></i> Octroyer un crédit
                    </a>
                </div>
                @endif
            </div>
        </div>

        {{-- Section Distributions de semences --}}
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="border-b px-6 py-4 flex justify-between items-center bg-gray-50/50">
                <h3 class="font-bold text-gray-700 flex items-center">
                    <i class="fas fa-seedling mr-2 text-green-600"></i> Distributions de semences
                </h3>
                <a href="{{ route('admin.distributions.create', ['cooperative_id' => $cooperative->id]) }}" 
                   class="text-xs bg-green-600 text-white px-3 py-1.5 rounded-lg hover:bg-green-700">
                    <i class="fas fa-plus mr-1"></i> Nouvelle distribution
                </a>
            </div>
            
            <div class="p-6">
                @if($cooperative->distributionsSemences->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-xs uppercase text-gray-400 border-b">
                                <th class="pb-3 font-bold">Date</th>
                                <th class="pb-3 font-bold">Semence</th>
                                <th class="pb-3 font-bold text-right">Quantité</th>
                                <th class="pb-3 font-bold text-right">Montant</th>
                                <th class="pb-3 font-bold text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @foreach($cooperative->distributionsSemences->take(5) as $distribution)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="py-3 text-sm">{{ $distribution->date_distribution->format('d/m/Y') }}</td>
                                <td class="py-3">{{ $distribution->semence->nom }} ({{ $distribution->semence->variete }})</td>
                                <td class="py-3 text-right">{{ number_format($distribution->quantite, 2) }} {{ $distribution->semence->unite }}</td>
                                <td class="py-3 text-right font-semibold">{{ number_format($distribution->montant_total, 0, ',', ' ') }} CFA</td>
                                <td class="py-3 text-center">
                                    <a href="{{ route('admin.distributions.show', $distribution) }}" class="text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-10">
                    <i class="fas fa-seedling text-5xl text-gray-300 mb-3"></i>
                    <p class="text-gray-400">Aucune distribution de semences.</p>
                </div>
                @endif
            </div>
        </div>

        {{-- Section Collectes --}}
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-bold text-gray-700 mb-4 flex items-center text-lg">
                <i class="fas fa-box-open mr-2 text-yellow-500"></i> Dernières collectes
            </h3>
            @forelse($cooperative->collectes->take(3) as $collecte)
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

        {{-- Liste des membres --}}
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-bold text-gray-700 mb-4 flex items-center text-lg">
                <i class="fas fa-users mr-2 text-purple-500"></i> Membres de la coopérative
            </h3>
            @if($cooperative->producteurs->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50">
                        <tr class="text-xs uppercase text-gray-500">
                            <th class="px-4 py-2">Code</th>
                            <th class="px-4 py-2">Nom</th>
                            <th class="px-4 py-2">Contact</th>
                            <th class="px-4 py-2 text-right">Superficie</th>
                            <th class="px-4 py-2 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach($cooperative->producteurs->take(5) as $producteur)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2 text-sm">{{ $producteur->code_producteur }}</td>
                            <td class="px-4 py-2">{{ $producteur->nom_complet }}</td>
                            <td class="px-4 py-2 text-sm">{{ $producteur->contact }}</td>
                            <td class="px-4 py-2 text-right text-sm">{{ number_format($producteur->superficie_totale, 2) }} ha</td>
                            <td class="px-4 py-2 text-center">
                                <a href="{{ route('admin.producteurs.show', $producteur) }}" class="text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @if($cooperative->producteurs->count() > 5)
                <div class="mt-3 text-center">
                    <a href="{{ route('admin.producteurs.index', ['cooperative_id' => $cooperative->id]) }}" class="text-sm text-purple-600 hover:underline">
                        Voir tous les {{ $cooperative->producteurs->count() }} membres
                    </a>
                </div>
                @endif
            </div>
            @else
            <div class="text-center py-10">
                <i class="fas fa-users text-5xl text-gray-300 mb-3"></i>
                <p class="text-gray-400">Aucun membre dans cette coopérative.</p>
                <a href="{{ route('admin.producteurs.create', ['cooperative_id' => $cooperative->id]) }}" class="text-purple-600 text-sm hover:underline mt-2 inline-block">
                    <i class="fas fa-plus mr-1"></i> Ajouter un producteur
                </a>
            </div>
            @endif
        </div>
    </div>
</div>

{{-- Description et notes --}}
@if($cooperative->description)
<div class="mt-6 bg-purple-50 border-l-4 border-purple-400 p-4 rounded-r-xl">
    <div class="flex">
        <i class="fas fa-file-alt text-purple-400 mt-1 mr-3"></i>
        <div>
            <h4 class="text-sm font-bold text-purple-800 uppercase">Description</h4>
            <p class="text-sm text-purple-700 mt-1">{{ $cooperative->description }}</p>
        </div>
    </div>
</div>
@endif
@endsection