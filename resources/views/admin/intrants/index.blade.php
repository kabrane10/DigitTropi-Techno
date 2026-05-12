@extends('layouts.admin')

@section('title', 'Gestion des intrants')
@section('header', 'Gestion des intrants')

@section('content')
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="p-6 border-b flex justify-between items-center flex-wrap gap-4">
        <div>
            <h2 class="text-xl font-semibold">Liste des intrants</h2>
            <p class="text-sm text-gray-500 mt-1">Suivi des stocks par zone et valeur financière</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.intrants.dashboard') }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">
                <i class="fas fa-chart-line mr-2"></i>Dashboard
            </a>
            <a href="{{ route('admin.intrants.alertes') }}" class="bg-orange-500 text-white px-4 py-2 rounded-lg hover:bg-orange-600 transition relative">
                <i class="fas fa-exclamation-triangle mr-2"></i>Alertes
                @php $nbAlertes = \App\Models\IntrantStock::whereRaw('stock_actuel <= seuil_alerte')->count(); @endphp
                @if($nbAlertes > 0)
                <span class="absolute -top-2 -right-2 bg-red-600 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full border-2 border-white">{{ $nbAlertes }}</span>
                @endif
            </a>
            <a href="{{ route('admin.intrants.create') }}" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-secondary transition">
                <i class="fas fa-plus mr-2"></i>Nouvel intrant
            </a>
        </div>
    </div>
    
    <div class="p-4 border-b bg-gray-50/50">
        <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="relative">
                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                <input type="text" name="search" placeholder="Nom ou code..." value="{{ request('search') }}"
                       class="w-full pl-10 pr-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition">
            </div>
            <select name="type" class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary/20 outline-none">
                <option value="">Tous les types</option>
                @foreach(['engrais', 'pesticide', 'herbicide', 'semence'] as $t)
                    <option value="{{ $t }}" {{ request('type') == $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
                @endforeach
            </select>
            <select name="zone" class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary/20 outline-none">
                <option value="">Toutes les zones</option>
                @foreach(['Centrale', 'Kara', 'Savanes'] as $z)
                    <option value="{{ $z }}" {{ request('zone') == $z ? 'selected' : '' }}>{{ $z }}</option>
                @endforeach
            </select>
            <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-lg hover:bg-black transition text-center font-medium">
                Appliquer les filtres
            </button>
        </form>
    </div>
    
    <div class="overflow-x-auto relative">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50 text-gray-600 uppercase text-[11px] font-bold tracking-wider">
                <tr>
                    <th class="px-6 py-4 border-b">Intrant</th>
                    <th class="px-6 py-4 border-b">Type</th>
                    <th class="px-6 py-4 border-b text-right">Prix Unit.</th>
                    <th class="px-6 py-4 border-b text-right">Stock Global</th>
                    <th class="px-6 py-4 border-b text-right text-primary">Valeur</th>
                    <th class="px-6 py-4 border-b">Statut</th>
                    <th class="px-6 py-4 border-b text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
                @forelse($intrants as $intrant)
                @php
                    $stockTotal = $intrant->stocks->sum('stock_actuel');
                    $valeurTotale = $stockTotal * $intrant->prix_unitaire;
                    $critique = $intrant->stocks->contains('est_critique', true);
                    
                    // Préparation des données pour le tooltip JS
                    $dataStocks = $intrant->stocks->map(function($s) {
                        return [
                            'zone' => $s->zone,
                            'stock' => $s->stock_actuel,
                            'critique' => $s->est_critique
                        ];
                    });
                @endphp
                <tr class="hover:bg-blue-50/30 transition">
                    <td class="px-6 py-4">
                        <div class="font-bold text-gray-800">{{ $intrant->nom }}</div>
                        <div class="text-[10px] font-mono text-gray-400">{{ $intrant->code_intrant }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase
                            @if($intrant->type == 'engrais') bg-green-100 text-green-700
                            @elseif($intrant->type == 'pesticide') bg-red-100 text-red-700
                            @elseif($intrant->type == 'herbicide') bg-yellow-100 text-yellow-700
                            @else bg-gray-100 text-gray-700 @endif">
                            {{ $intrant->type_label }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right font-medium">{{ number_format($intrant->prix_unitaire, 0, ',', ' ') }} <small>CFA</small></td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end group">
                            <span class="font-bold {{ $stockTotal <= 100 ? 'text-red-600' : 'text-gray-700' }}">
                                {{ number_format($stockTotal) }} {{ $intrant->unite }}
                            </span>
                            <i class="fas fa-info-circle ml-2 text-gray-300 cursor-help hover:text-primary transition stock-hover-trigger"
                               data-nom="{{ $intrant->nom }}"
                               data-unite="{{ $intrant->unite }}"
                               data-stocks='@json($dataStocks)'></i>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-right font-bold text-primary">
                        {{ number_format($valeurTotale, 0, ',', ' ') }} <small>CFA</small>
                    </td>
                    <td class="px-6 py-4 text-xs font-semibold">
                        @if($critique)
                            <span class="flex items-center text-red-600">
                                <span class="w-1.5 h-1.5 bg-red-600 rounded-full mr-1.5 animate-pulse"></span>
                                Critique
                            </span>
                        @else
                            <span class="text-green-600 flex items-center">
                                <span class="w-1.5 h-1.5 bg-green-600 rounded-full mr-1.5"></span>
                                Optimal
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex justify-center gap-3">
                            <a href="{{ route('admin.intrants.show', $intrant) }}" class="text-gray-400 hover:text-blue-600 transition"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('admin.intrants.edit', $intrant) }}" class="text-gray-400 hover:text-green-600 transition"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('admin.intrants.destroy', $intrant) }}" method="POST" class="inline delete-confirm">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-gray-400 hover:text-red-600 transition"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-6 py-12 text-center text-gray-400">Aucun résultat trouvé...</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div id="floatingTooltip" class="fixed hidden z-[9999] pointer-events-none bg-gray-900 text-white p-3 rounded-xl shadow-2xl border border-white/10 w-64">
    <p id="tooltipTitle" class="text-xs font-bold border-b border-white/20 pb-2 mb-2 uppercase tracking-tighter text-blue-300"></p>
    <div id="tooltipContent" class="space-y-1.5 text-xs"></div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const tooltip = document.getElementById('floatingTooltip');
        const tooltipTitle = document.getElementById('tooltipTitle');
        const tooltipContent = document.getElementById('tooltipContent');
        
        document.querySelectorAll('.stock-hover-trigger').forEach(trigger => {
            trigger.addEventListener('mouseenter', (e) => {
                const stocks = JSON.parse(trigger.dataset.stocks);
                const nom = trigger.dataset.nom;
                const unite = trigger.dataset.unite;
                
                tooltipTitle.innerHTML = `📊 ${nom}`;
                tooltipContent.innerHTML = stocks.map(s => `
                    <div class="flex justify-between items-center">
                        <span class="text-gray-400">${s.zone}</span>
                        <span class="font-bold ${s.critique ? 'text-red-400' : 'text-green-400'}">
                            ${Number(s.stock).toLocaleString()} ${unite}
                        </span>
                    </div>
                `).join('');
                
                tooltip.classList.remove('hidden');
            });
            
            trigger.addEventListener('mousemove', (e) => {
                // Positionnement intelligent pour éviter de sortir de l'écran
                let x = e.clientX + 15;
                let y = e.clientY + 15;
                
                if (x + 256 > window.innerWidth) x = e.clientX - 270;
                if (y + 150 > window.innerHeight) y = e.clientY - 160;
                
                tooltip.style.left = x + 'px';
                tooltip.style.top = y + 'px';
            });
            
            trigger.addEventListener('mouseleave', () => {
                tooltip.classList.add('hidden');
            });
        });
    });
</script>
@endsection