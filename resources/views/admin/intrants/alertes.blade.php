@extends('layouts.admin')

@section('title', 'Alertes stock')
@section('header', '⚠️ Gestion des alertes et réapprovisionnement')

@section('content')
<div class="space-y-6">
    <!-- Barre d'actions rapides -->
    <div class="bg-white rounded-xl shadow-sm p-4 flex justify-between items-center flex-wrap gap-4">
        <div class="flex gap-3">
            <button onclick="toggleView()" id="toggleViewBtn" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition">
                <i class="fas fa-list mr-2"></i>Vue en liste
            </button>
            <button onclick="exportAlertes()" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition">
                <i class="fas fa-file-excel mr-2"></i>Exporter la liste d'achat
            </button>
        </div>
        <div class="flex gap-3">
            <div class="relative">
                <input type="text" id="searchInput" placeholder="Rechercher un intrant..." class="pl-10 pr-4 py-2 border rounded-lg w-64">
                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
            </div>
            <select id="typeFilter" class="px-4 py-2 border rounded-lg">
                <option value="all">Tous les types</option>
                <option value="engrais"> Engrais</option>
                <option value="pesticide"> Pesticide</option>
                <option value="herbicide"> Herbicide</option>
                <option value="semence"> Semence</option>
            </select>
        </div>
    </div>

    <!-- Onglets par région -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="border-b">
            <div class="flex">
                @php
                    $zones = ['Centrale', 'Kara', 'Savanes'];
                    $hasAlertes = [];
                    foreach($zones as $zone) {
                        $hasAlertes[$zone] = $stocksCritiques->filter(function($s) use ($zone) {
                            return $s->zone == $zone;
                        })->count() > 0;
                    }
                @endphp
                @foreach($zones as $index => $zone)
                <button class="tab-btn px-6 py-3 text-sm font-medium transition-all {{ $index === 0 ? 'border-b-2 border-primary text-primary' : 'text-gray-500 hover:text-gray-700' }}"
                        data-zone="{{ $zone }}">
                    <i class="fas fa-map-marker-alt mr-2"></i>{{ $zone }}
                    @if($hasAlertes[$zone])
                    <span class="ml-2 bg-red-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $stocksCritiques->filter(fn($s) => $s->zone == $zone)->count() }}</span>
                    @endif
                </button>
                @endforeach
            </div>
        </div>

        <!-- Vue en cartes (par défaut) -->
        <div id="cardsView" class="p-6">
            @foreach($zones as $zone)
            <div class="zone-section" data-zone="{{ $zone }}" style="display: {{ $loop->first ? 'block' : 'none' }}">
                <div class="mb-4">
                    <h3 class="text-lg font-semibold text-dark"> Zone {{ $zone }}</h3>
                    <p class="text-sm text-gray-500">Produits nécessitant une action immédiate</p>
                </div>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    @php
                        $zoneStocks = $stocksCritiques->filter(fn($s) => $s->zone == $zone)->sortBy(function($s) {
                            return ($s->stock_actuel / $s->seuil_alerte) * 100;
                        });
                    @endphp
                    @forelse($zoneStocks as $stock)
                    @php
                        $pourcentage = ($stock->stock_actuel / $stock->seuil_alerte) * 100;
                        $besoinReappro = max(0, $stock->seuil_alerte - $stock->stock_actuel);
                        $coutReconstitution = $besoinReappro * $stock->intrant->prix_unitaire;
                        
                        // Trouver du stock dans d'autres zones pour suggestion de transfert
                        $autreZoneStock = $stock->intrant->stocks->first(function($s) use ($zone) {
                            return $s->zone != $zone && $s->stock_actuel > $s->seuil_alerte;
                        });
                    @endphp
                    <div class="border-l-4 {{ $pourcentage <= 20 ? 'border-red-600' : 'border-orange-500' }} bg-white shadow-lg rounded-r-xl hover:shadow-xl transition-all duration-300">
                        <div class="p-5">
                            <!-- En-tête -->
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <span class="text-xs font-bold uppercase text-gray-400">{{ $stock->intrant->type_label }}</span>
                                    <h3 class="text-lg font-bold text-dark">{{ $stock->intrant->nom }}</h3>
                                </div>
                                <div class="text-right">
                                    <span class="px-2 py-1 text-xs rounded-full {{ $pourcentage <= 20 ? 'bg-red-100 text-red-700' : 'bg-orange-100 text-orange-700' }} font-bold">
                                        {{ $pourcentage <= 20 ? '⚠️ CRITIQUE' : '⚠️ ALERTE' }}
                                    </span>
                                </div>
                            </div>

                            <!-- Niveau de stock -->
                            <div class="mb-4">
                                <div class="flex justify-between mb-1 text-sm">
                                    <span class="text-gray-600">Niveau de réserve</span>
                                    <span class="font-bold {{ $pourcentage <= 20 ? 'text-red-600' : 'text-orange-600' }}">{{ number_format($pourcentage, 1) }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2.5 overflow-hidden">
                                    <div class="{{ $pourcentage <= 20 ? 'bg-red-600' : 'bg-orange-500' }} h-2.5 rounded-full transition-all" style="width: {{ min($pourcentage, 100) }}%"></div>
                                </div>
                            </div>

                            <!-- Statistiques -->
                            <div class="grid grid-cols-2 gap-3 mb-4">
                                <div>
                                    <p class="text-xs text-gray-500">Stock actuel</p>
                                    <p class="text-xl font-bold {{ $pourcentage <= 20 ? 'text-red-600' : 'text-orange-600' }}">
                                        {{ number_format($stock->stock_actuel) }} {{ $stock->unite }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Seuil d'alerte</p>
                                    <p class="text-xl font-bold text-gray-700">{{ number_format($stock->seuil_alerte) }} {{ $stock->unite }}</p>
                                </div>
                            </div>

                            <!-- Analyse financière -->
                            <div class="bg-gray-50 rounded-lg p-3 mb-4">
                                <p class="text-xs text-gray-500 mb-1">Coût de reconstitution estimé</p>
                                <p class="text-lg font-bold text-primary">{{ number_format($coutReconstitution, 0, ',', ' ') }} CFA</p>
                                <p class="text-xs text-gray-400">Pour remonter au seuil d'alerte</p>
                            </div>

                            <!-- Suggestion de transfert -->
                            @if($autreZoneStock)
                            <div class="bg-blue-50 rounded-lg p-3 mb-4 border-l-4 border-blue-500">
                                <div class="flex items-start gap-2">
                                    <i class="fas fa-lightbulb text-blue-500 mt-0.5"></i>
                                    <div>
                                        <p class="text-xs text-blue-700 font-semibold">💡 Suggestion de transfert</p>
                                        <p class="text-sm text-blue-800">
                                            Transférer <strong>{{ number_format(min($autreZoneStock->stock_actuel - $autreZoneStock->seuil_alerte, $besoinReappro)) }} {{ $stock->unite }}</strong> 
                                            depuis la zone <strong>{{ $autreZoneStock->zone }}</strong> 
                                            (Stock disponible: {{ number_format($autreZoneStock->stock_actuel) }} {{ $stock->unite }})
                                        </p>
                                        <button onclick="quickTransfer('{{ $stock->intrant->id }}', '{{ $stock->zone }}', '{{ $autreZoneStock->zone }}', {{ min($autreZoneStock->stock_actuel - $autreZoneStock->seuil_alerte, $besoinReappro) }})" 
                                                class="mt-2 text-xs bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">
                                            <i class="fas fa-exchange-alt mr-1"></i>Transférer maintenant
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- Actions rapides -->
                            <div class="flex gap-2 pt-3 border-t border-gray-100">
                                <button onclick="quickReappro('{{ $stock->intrant->id }}', '{{ $stock->zone }}', '{{ $besoinReappro }}')" 
                                        class="flex-1 bg-primary text-white px-3 py-2 rounded-lg text-sm hover:bg-secondary transition flex items-center justify-center">
                                    <i class="fas fa-shopping-cart mr-2"></i>Commander
                                </button>
                                <button onclick="openTransferModal('{{ $stock->intrant->id }}', '{{ $stock->zone }}')" 
                                        class="flex-1 bg-blue-500 text-white px-3 py-2 rounded-lg text-sm hover:bg-blue-600 transition flex items-center justify-center">
                                    <i class="fas fa-exchange-alt mr-2"></i>Transférer
                                </button>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-2 text-center py-12 text-gray-500">
                        <i class="fas fa-check-circle text-4xl mb-2 text-green-500"></i>
                        <p>Aucune alerte dans cette zone</p>
                    </div>
                    @endforelse
                </div>
            </div>
            @endforeach
        </div>

        <!-- Vue en liste (tableau) -->
        <div id="listView" class="p-6 hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Zone</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Intrant</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Type</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500">Stock actuel</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500">Seuil</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500">Criticité</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500">Coût reconstitution</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach($stocksCritiques->sortBy(function($s) {
                            return ($s->stock_actuel / $s->seuil_alerte) * 100;
                        }) as $stock)
                        @php
                            $pourcentage = ($stock->stock_actuel / $stock->seuil_alerte) * 100;
                            $besoinReappro = max(0, $stock->seuil_alerte - $stock->stock_actuel);
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">{{ $stock->zone }}</td>
                            <td class="px-4 py-3 font-medium">{{ $stock->intrant->nom }}</td>
                            <td class="px-4 py-3">{{ $stock->intrant->type_label }}</td>
                            <td class="px-4 py-3 text-right font-bold {{ $pourcentage <= 20 ? 'text-red-600' : 'text-orange-600' }}">
                                {{ number_format($stock->stock_actuel) }} {{ $stock->unite }}
                            </td>
                            <td class="px-4 py-3 text-right">{{ number_format($stock->seuil_alerte) }} {{ $stock->unite }}</td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <span class="text-sm">{{ number_format($pourcentage, 1) }}%</span>
                                    <div class="w-16 bg-gray-200 rounded-full h-1.5">
                                        <div class="{{ $pourcentage <= 20 ? 'bg-red-600' : 'bg-orange-500' }} h-1.5 rounded-full" style="width: {{ min($pourcentage, 100) }}%"></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-right">{{ number_format($besoinReappro * $stock->intrant->prix_unitaire, 0, ',', ' ') }} CFA</td>
                            <td class="px-4 py-3 text-center">
                                <button onclick="quickReappro('{{ $stock->intrant->id }}', '{{ $stock->zone }}', '{{ $besoinReappro }}')" 
                                        class="text-green-600 hover:text-green-800 mr-2" title="Commander">
                                    <i class="fas fa-shopping-cart"></i>
                                </button>
                                <button onclick="openTransferModal('{{ $stock->intrant->id }}', '{{ $stock->zone }}')" 
                                        class="text-blue-600 hover:text-blue-800" title="Transférer">
                                    <i class="fas fa-exchange-alt"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal réapprovisionnement rapide -->
<div id="reapproModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center">
    <div class="bg-white rounded-xl p-6 max-w-md w-full">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold">Réapprovisionnement rapide</h3>
            <button onclick="closeReapproModal()" class="text-gray-400 hover:text-gray-600">&times;</button>
        </div>
        <form id="reapproForm" method="POST" action="">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold mb-2">Intrant</label>
                    <input type="text" id="reapproIntrant" class="w-full px-4 py-2 border rounded-lg bg-gray-100" disabled>
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-2">Zone</label>
                    <input type="text" id="reapproZone" class="w-full px-4 py-2 border rounded-lg bg-gray-100" disabled>
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-2">Quantité recommandée *</label>
                    <input type="number" step="0.01" name="quantite" id="reapproQuantite" required class="w-full px-4 py-2 border rounded-lg">
                    <p class="text-xs text-gray-500 mt-1" id="reapproInfo"></p>
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-2">Motif</label>
                    <select name="motif" class="w-full px-4 py-2 border rounded-lg">
                        <option value="Achat">Achat</option>
                        <option value="Réapprovisionnement">Réapprovisionnement urgent</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-2">Référence facture</label>
                    <input type="text" name="reference" class="w-full px-4 py-2 border rounded-lg" placeholder="N° commande / facture">
                </div>
            </div>
            <div class="mt-6 flex justify-end gap-3">
                <button type="button" onclick="closeReapproModal()" class="px-4 py-2 border rounded-lg">Annuler</button>
                <button type="submit" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-secondary">Confirmer la commande</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Transfert  -->
<div id="transferModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center">
    <div class="bg-white rounded-xl p-6 max-w-md w-full">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold"> Transférer du stock</h3>
            <button onclick="closeTransferModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <form id="transferForm" method="POST" action="{{ route('admin.intrants.transferer', $stock->intrant_id) }}">
            @csrf
            <input type="hidden" name="source_zone" value="{{ $stock->zone }}">
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold mb-2">De (zone source)</label>
                    <input type="text" value="{{ $stock->zone }}" class="w-full px-4 py-2 border rounded-lg bg-gray-100" disabled>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold mb-2">Vers (zone destination) *</label>
                    <select name="destination_zone" id="destination_zone" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                        <option value="">-- Sélectionnez une zone --</option>
                        @foreach($zones as $zone)
                        <option value="{{ $zone->zone }}" data-stock="{{ $zone->stock_actuel }}">
                            {{ $zone->zone }} (stock actuel: {{ number_format($zone->stock_actuel) }} {{ $zone->unite }})
                        </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold mb-2">Quantité à transférer *</label>
                    <input type="number" step="0.01" name="quantite" id="transferQuantite" required 
                           max="{{ $stock->stock_actuel }}" 
                           class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    <p class="text-xs text-gray-500 mt-1">Maximum disponible: {{ number_format($stock->stock_actuel) }} {{ $stock->unite }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold mb-2">Motif du transfert</label>
                    <textarea name="notes" rows="2" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary" 
                              placeholder="Raison du transfert..."></textarea>
                </div>
            </div>
            
            <div class="mt-6 flex justify-end gap-3">
                <button type="button" onclick="closeTransferModal()" class="px-4 py-2 border rounded-lg hover:bg-gray-50 transition">
                    Annuler
                </button>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">
                    <i class="fas fa-exchange-alt mr-2"></i>Transférer
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    let currentView = 'cards';
    let currentZone = 'Centrale';

    // Gestion des onglets
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const zone = this.dataset.zone;
            currentZone = zone;
            
            document.querySelectorAll('.tab-btn').forEach(b => {
                b.classList.remove('border-b-2', 'border-primary', 'text-primary');
                b.classList.add('text-gray-500');
            });
            this.classList.add('border-b-2', 'border-primary', 'text-primary');
            this.classList.remove('text-gray-500');
            
            document.querySelectorAll('.zone-section').forEach(section => {
                section.style.display = section.dataset.zone === zone ? 'block' : 'none';
            });
        });
    });

    // Filtres
    const searchInput = document.getElementById('searchInput');
    const typeFilter = document.getElementById('typeFilter');
    
    function filterCards() {
        const search = searchInput.value.toLowerCase();
        const type = typeFilter.value;
        
        document.querySelectorAll('.zone-section .grid > div').forEach(card => {
            const title = card.querySelector('h3')?.textContent.toLowerCase() || '';
            const cardType = card.querySelector('.text-xs.font-bold.uppercase')?.textContent.toLowerCase() || '';
            
            let show = true;
            if (search && !title.includes(search)) show = false;
            if (type !== 'all' && !cardType.includes(type)) show = false;
            card.style.display = show ? 'block' : 'none';
        });
    }
    
    searchInput.addEventListener('input', filterCards);
    typeFilter.addEventListener('change', filterCards);

    // Changement de vue
    function toggleView() {
        const cardsView = document.getElementById('cardsView');
        const listView = document.getElementById('listView');
        const btn = document.getElementById('toggleViewBtn');
        
        if (currentView === 'cards') {
            cardsView.classList.add('hidden');
            listView.classList.remove('hidden');
            btn.innerHTML = '<i class="fas fa-th-large mr-2"></i>Vue en cartes';
            currentView = 'list';
        } else {
            cardsView.classList.remove('hidden');
            listView.classList.add('hidden');
            btn.innerHTML = '<i class="fas fa-list mr-2"></i>Vue en liste';
            currentView = 'cards';
        }
    }

    // Export des alertes
    function exportAlertes() {
        let csv = "Zone,Intrant,Type,Stock actuel,Seuil,Criticité,Coût reconstitution\n";
        @foreach($stocksCritiques as $stock)
        csv += "{{ $stock->zone }},{{ $stock->intrant->nom }},{{ $stock->intrant->type_label }},{{ $stock->stock_actuel }},{{ $stock->seuil_alerte }},{{ number_format(($stock->stock_actuel / $stock->seuil_alerte) * 100, 1) }}%,{{ number_format(max(0, $stock->seuil_alerte - $stock->stock_actuel) * $stock->intrant->prix_unitaire, 0, ',', '') }}\n";
        @endforeach
        
        const blob = new Blob(["\uFEFF" + csv], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement('a');
        const url = URL.createObjectURL(blob);
        link.href = url;
        link.setAttribute('download', 'alertes-stock.csv');
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        URL.revokeObjectURL(url);
    }

    // Réapprovisionnement rapide
    function quickReappro(intrantId, zone, quantiteSuggeree) {
        const modal = document.getElementById('reapproModal');
        const form = document.getElementById('reapproForm');
        const intrantInput = document.getElementById('reapproIntrant');
        const zoneInput = document.getElementById('reapproZone');
        const quantiteInput = document.getElementById('reapproQuantite');
        const infoSpan = document.getElementById('reapproInfo');
        
        // Récupérer le nom de l'intrant
        fetch(`/admin/intrants/${intrantId}`)
            .then(response => response.json())
            .then(data => {
                intrantInput.value = data.nom;
                zoneInput.value = zone;
                quantiteInput.value = quantiteSuggeree;
                infoSpan.textContent = `Quantité suggérée pour remonter au seuil d'alerte`;
                form.action = `/admin/intrants/${intrantId}/stock/${zone}/ajouter`;
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            });
    }
    
    function closeReapproModal() {
        const modal = document.getElementById('reapproModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    // Transfert rapide
    function quickTransfer(intrantId, sourceZone, destZone, quantite) {
        if (confirm(`Transférer ${quantite} kg de ${sourceZone} vers ${destZone} ?`)) {
            fetch(`/admin/intrants/transferer/${intrantId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    source_zone: destZone,
                    destination_zone: sourceZone,
                    quantite: quantite,
                    notes: 'Transfert automatique depuis alerte'
                })
            }).then(response => {
                if (response.ok) {
                    location.reload();
                } else {
                    alert('Erreur lors du transfert');
                }
            });
        }
    }
    
    function openTransferModal() {
        const modal = document.getElementById('transferModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        
        // Réinitialiser le formulaire
        document.getElementById('destination_zone').value = '';
        document.getElementById('transferQuantite').value = '';
    }
    
    function closeTransferModal() {
        const modal = document.getElementById('transferModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
    
    // Validation de la quantité avant soumission
    document.getElementById('transferForm')?.addEventListener('submit', function(e) {
        const quantite = parseFloat(document.getElementById('transferQuantite').value);
        const maxQuantite = {{ $stock->stock_actuel }};
        
        if (isNaN(quantite) || quantite <= 0) {
            e.preventDefault();
            alert('Veuillez saisir une quantité valide');
            return false;
        }
        
        if (quantite > maxQuantite) {
            e.preventDefault();
            alert('La quantité ne peut pas dépasser le stock disponible (' + maxQuantite.toLocaleString() + ' {{ $stock->unite }})');
            return false;
        }
        
        if (!document.getElementById('destination_zone').value) {
            e.preventDefault();
            alert('Veuillez sélectionner une zone de destination');
            return false;
        }
    });
</script>
@endsection