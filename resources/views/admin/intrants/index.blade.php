@extends('layouts.admin')

@section('title', 'Gestion des intrants')
@section('header', ' Gestion des intrants')

@section('content')
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b flex justify-between items-center flex-wrap gap-4">
        <div>
            <h2 class="text-xl font-semibold">Liste des intrants</h2>
            <p class="text-sm text-gray-500 mt-1">Gestion centralisée des stocks, valeur financière et alertes</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.intrants.dashboard') }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                <i class="fas fa-chart-line mr-2"></i>Dashboard
            </a>
            <a href="{{ route('admin.intrants.alertes') }}" class="bg-orange-500 text-white px-4 py-2 rounded-lg hover:bg-orange-600">
                <i class="fas fa-exclamation-triangle mr-2"></i>Alertes
                @php $nbAlertes = \App\Models\IntrantStock::whereRaw('stock_actuel <= seuil_alerte')->count(); @endphp
                @if($nbAlertes > 0)
                <span class="ml-2 bg-red-500 text-white px-2 py-0.5 rounded-full text-xs">{{ $nbAlertes }}</span>
                @endif
            </a>
            <a href="{{ route('admin.intrants.create') }}" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-secondary">
                <i class="fas fa-plus mr-2"></i>Nouvel intrant
            </a>
        </div>
    </div>
    
    <!-- Filtres avancés -->
    <div class="p-6 border-b bg-gray-50">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <input type="text" name="search" placeholder="Rechercher..." value="{{ request('search') }}"
                   class="px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            <select name="type" class="px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                <option value="">Tous les types</option>
                <option value="engrais" {{ request('type') == 'engrais' ? 'selected' : '' }}> Engrais</option>
                <option value="pesticide" {{ request('type') == 'pesticide' ? 'selected' : '' }}> Pesticide</option>
                <option value="herbicide" {{ request('type') == 'herbicide' ? 'selected' : '' }}> Herbicide</option>
                <option value="semence" {{ request('type') == 'semence' ? 'selected' : '' }}> Semence</option>
            </select>
            <select name="zone" class="px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                <option value="">Toutes les zones</option>
                <option value="Centrale" {{ request('zone') == 'Centrale' ? 'selected' : '' }}> Centrale</option>
                <option value="Kara" {{ request('zone') == 'Kara' ? 'selected' : '' }}> Kara</option>
                <option value="Savanes" {{ request('zone') == 'Savanes' ? 'selected' : '' }}> Savanes</option>
            </select>
            <button type="submit" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-secondary">
                <i class="fas fa-search mr-2"></i>Filtrer
            </button>
        </form>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full min-w-[800px]">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Code</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Intrant</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Type</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500">Prix unitaire</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500">Stock total</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500">Valeur totale</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Statut global</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($intrants as $intrant)
                @php
                    $stockTotal = $intrant->stocks->sum('stock_actuel');
                    $valeurTotale = $intrant->stocks->sum(function($s) use ($intrant) {
                        return $s->stock_actuel * $intrant->prix_unitaire;
                    });
                    $stocksData = $intrant->stocks->map(fn($s) => [
                        'zone' => $s->zone,
                        'stock' => $s->stock_actuel,
                        'unite' => $s->unite,
                        'critique' => $s->est_critique
                    ])->toJson();
                @endphp
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm font-mono">{{ $intrant->code_intrant }}</td>
                    <td class="px-6 py-4 font-medium">{{ $intrant->nom }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full 
                            @if($intrant->type == 'engrais') bg-green-100 text-green-800
                            @elseif($intrant->type == 'pesticide') bg-red-100 text-red-800
                            @elseif($intrant->type == 'herbicide') bg-yellow-100 text-yellow-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ $intrant->type_label }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">{{ number_format($intrant->prix_unitaire, 0, ',', ' ') }} CFA/{{ $intrant->unite }}</td>
                    <td class="px-6 py-4 text-right">
                        <span class="stock-hover-trigger font-bold cursor-help {{ $stockTotal <= 100 ? 'text-red-600' : 'text-green-600' }}"
                              data-stocks='{{ $stocksData }}'
                              data-nom="{{ $intrant->nom }}"
                              data-unite="{{ $intrant->unite }}">
                            {{ number_format($stockTotal) }} {{ $intrant->unite }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right font-semibold text-blue-600">
                        {{ number_format($valeurTotale, 0, ',', ' ') }} CFA
                    </td>
                    <td class="px-6 py-4">
                        @php $zonesCritiques = $intrant->stocks->filter->est_critique->pluck('zone')->implode(', '); @endphp
                        @if($zonesCritiques)
                            <div class="flex items-center">
                                <span class="w-2 h-2 bg-red-500 rounded-full mr-2"></span>
                                <span class="text-sm text-red-600">Stock critique</span>
                            </div>
                        @elseif($stockTotal <= ($intrant->seuil_alerte_global ?? 100))
                            <span class="text-sm text-orange-600">⚠️ Stock faible</span>
                        @else
                            <span class="text-sm text-green-600">✅ Normal</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center space-x-2">
                        <a href="{{ route('admin.intrants.show', $intrant) }}" class="text-blue-600 hover:text-blue-800"><i class="fas fa-eye"></i></a>
                        <a href="{{ route('admin.intrants.edit', $intrant) }}" class="text-green-600 hover:text-green-800"><i class="fas fa-edit"></i></a>
                        <form action="{{ route('admin.intrants.destroy', $intrant) }}" method="POST" class="inline delete-confirm">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="px-6 py-8 text-center text-gray-500">Aucun intrant trouvé</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-6">{{ $intrants->links() }}</div>
</div>

<!-- Indicateur de performance des stocks -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-6">
    <div class="bg-white rounded-xl shadow-sm p-4">
        <p class="text-gray-500 text-sm">Valeur totale du stock</p>
        <p class="text-2xl font-bold text-primary" id="valeurTotaleStock">
            @php
                $valeurGlobale = \App\Models\Intrant::with('stocks')->get()->sum(function($i) {
                    return $i->stocks->sum('stock_actuel') * $i->prix_unitaire;
                });
            @endphp
            {{ number_format($valeurGlobale, 0, ',', ' ') }} CFA
        </p>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-4">
        <p class="text-gray-500 text-sm">Produits en stock</p>
        <p class="text-2xl font-bold text-primary">{{ \App\Models\Intrant::count() }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-4">
        <p class="text-gray-500 text-sm">Alertes actives</p>
        <p class="text-2xl font-bold text-red-600">{{ \App\Models\IntrantStock::whereRaw('stock_actuel <= seuil_alerte')->count() }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-4">
        <p class="text-gray-500 text-sm">Rotation estimée</p>
        <p class="text-2xl font-bold text-primary">-</p>
    </div>
</div>
<script>
    // Tooltip flottant qui suit la souris
    const tooltip = document.getElementById('floatingTooltip');
    const tooltipTitle = document.getElementById('tooltipTitle');
    const tooltipContent = document.getElementById('tooltipContent');
    let currentTimeout = null;
    
    document.querySelectorAll('.stock-hover-trigger').forEach(trigger => {
        trigger.addEventListener('mouseenter', (e) => {
            const stocks = JSON.parse(trigger.dataset.stocks);
            const nom = trigger.dataset.nom;
            const unite = trigger.dataset.unite;
            
            tooltipTitle.innerHTML = `📊 ${nom} - Stock par zone`;
            tooltipContent.innerHTML = stocks.map(s => `
                <div class="flex justify-between items-center gap-4 py-1">
                    <span class="font-medium">📍 ${s.zone}:</span>
                    <span class="${s.critique ? 'text-red-300 font-bold' : 'text-green-300'}">
                        ${Number(s.stock).toLocaleString()} ${unite}
                    </span>
                </div>
            `).join('');
            
            tooltip.classList.remove('hidden');
            tooltip.style.left = (e.clientX + 15) + 'px';
            tooltip.style.top = (e.clientY + 15) + 'px';
        });
        
        trigger.addEventListener('mousemove', (e) => {
            tooltip.style.left = (e.clientX + 15) + 'px';
            tooltip.style.top = (e.clientY + 15) + 'px';
        });
        
        trigger.addEventListener('mouseleave', () => {
            tooltip.classList.add('hidden');
        });
    });
</script>
@endsection