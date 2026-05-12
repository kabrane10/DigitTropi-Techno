@extends('layouts.admin')

@section('title', 'Gestion du stock')
@section('header', 'Stock - ' . $stock->intrant->nom . ' (' . $stock->zone . ')')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Cartes d'information -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-primary">
            <p class="text-gray-500 text-sm">Stock actuel</p>
            <p class="text-2xl font-bold {{ $stock->est_critique ? 'text-red-600' : 'text-green-600' }}">
                {{ number_format($stock->stock_actuel) }} {{ $stock->unite }}
            </p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-yellow-500">
            <p class="text-gray-500 text-sm">Seuil d'alerte</p>
            <p class="text-2xl font-bold">{{ number_format($stock->seuil_alerte) }} {{ $stock->unite }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-blue-500">
            <p class="text-gray-500 text-sm">Valeur du stock</p>
            <p class="text-2xl font-bold text-blue-600">
                {{ number_format($stock->stock_actuel * $stock->intrant->prix_unitaire, 0, ',', ' ') }} CFA
            </p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-purple-500">
            <p class="text-gray-500 text-sm">Emplacement</p>
            <p class="text-xl font-bold">{{ $stock->emplacement ?? 'Non défini' }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-green-500">
            <p class="text-gray-500 text-sm">Dernier mouvement</p>
            <p class="text-sm font-medium">{{ $stock->mouvements()->latest()->first()?->created_at->format('d/m/Y H:i') ?? '-' }}</p>
        </div>
    </div>
    
    <!-- Actions rapides -->
    <div class="flex gap-3 mb-6">
        <button onclick="openModal('entree')" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 flex items-center">
            <i class="fas fa-plus-circle mr-2"></i>Entrée de stock
        </button>
        <button onclick="openModal('sortie')" class="bg-orange-500 text-white px-4 py-2 rounded-lg hover:bg-orange-600 flex items-center">
            <i class="fas fa-minus-circle mr-2"></i>Sortie de stock
        </button>
        <button onclick="openTransferModal()" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 flex items-center">
            <i class="fas fa-exchange-alt mr-2"></i>Transférer vers une autre zone
        </button>
    </div>
    
    <!-- Historique des mouvements -->
    <div class="bg-white rounded-xl shadow-sm">
     《 <div class="p-6 border-b">
            <div class="flex justify-between items-center flex-wrap gap-4">
                <h3 class="text-lg font-semibold">Historique des mouvements</h3>
                <div class="flex gap-2">
                    <select id="filterType" class="px-3 py-1 border rounded-lg text-sm">
                        <option value="all">Tous les mouvements</option>
                        <option value="entree">Entrées</option>
                        <option value="sortie">Sorties</option>
                    </select>
                    <select id="filterMotif" class="px-3 py-1 border rounded-lg text-sm">
                        <option value="all">Tous les motifs</option>
                        <option value="Achat">Achat</option>
                        <option value="Réapprovisionnement">Réapprovisionnement</option>
                        <option value="Utilisation">Utilisation terrain</option>
                        <option value="Distribution">Distribution producteurs</option>
                        <option value="Péremption">Péremption</option>
                        <option value="Transfert">Transfert</option>
                    </select>
                    <input type="date" id="filterDate" class="px-3 py-1 border rounded-lg text-sm">
                </div>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full" id="mouvementsTable">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Date</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500">Type</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500">Quantité</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Motif</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Référence</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Utilisateur</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Notes</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($mouvements as $mvt)
                    <tr class="hover:bg-gray-50" data-type="{{ $mvt->type }}" data-motif="{{ $mvt->motif }}" data-date="{{ $mvt->created_at->format('Y-m-d') }}">
                        <td class="px-6 py-3 text-sm">{{ $mvt->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-6 py-3 text-center">
                            <span class="px-2 py-1 text-xs rounded-full {{ $mvt->type == 'entree' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $mvt->type == 'entree' ? '➕ Entrée' : '➖ Sortie' }}
                            </span>
                        </td>
                        <td class="px-6 py-3 text-right font-semibold {{ $mvt->type == 'entree' ? 'text-green-600' : 'text-red-600' }}">
                            {{ number_format($mvt->quantite) }} {{ $stock->unite }}
                        </td>
                        <td class="px-6 py-3 text-sm">{{ $mvt->motif }}</td>
                        <td class="px-6 py-3 text-sm">{{ $mvt->reference ?? '-' }}</td>
                        <td class="px-6 py-3 text-sm">{{ $mvt->user->nom ?? '-' }}</td>
                        <td class="px-6 py-3 text-sm">{{ Str::limit($mvt->notes ?? '-', 30) }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="px-6 py-8 text-center text-gray-500">Aucun mouvement</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-6">{{ $mouvements->links() }}</div>
    </div>
</div>

<!-- Modal Entrée/Sortie -->
<div id="stockModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center">
    <div class="bg-white rounded-xl p-6 max-w-md w-full">
        <div class="flex justify-between items-center mb-4">
            <h3 id="modalTitle" class="text-xl font-bold">Ajouter du stock</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">&times;</button>
        </div>
        <form id="stockForm" method="POST" action="">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold mb-2">Quantité ({{ $stock->unite }}) *</label>
                    <input type="number" step="0.01" name="quantite" id="modalQuantite" required class="w-full px-4 py-2 border rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-2">Motif *</label>
                    <select name="motif" id="modalMotif" required class="w-full px-4 py-2 border rounded-lg">
                        <option value="">Sélectionnez</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-2">Référence</label>
                    <input type="text" name="reference" class="w-full px-4 py-2 border rounded-lg" placeholder="N° facture, bon de livraison...">
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-2">Notes</label>
                    <textarea name="notes" rows="2" class="w-full px-4 py-2 border rounded-lg"></textarea>
                </div>
            </div>
            <div class="mt-6 flex justify-end gap-3">
                <button type="button" onclick="closeModal()" class="px-4 py-2 border rounded-lg">Annuler</button>
                <button type="submit" class="px-4 py-2 rounded-lg text-white" id="modalSubmitBtn">Confirmer</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Transfert -->
<div id="transferModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center">
    <div class="bg-white rounded-xl p-6 max-w-md w-full">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold">Transférer du stock</h3>
            <button onclick="closeTransferModal()" class="text-gray-400 hover:text-gray-600">&times;</button>
        </div>
        <form action="{{ route('admin.intrants.transferer', ['id' => $stock->intrant_id]) }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold mb-2">De *</label>
                    <input type="text" value="{{ $stock->zone }}" class="w-full px-4 py-2 border rounded-lg bg-gray-100" disabled>
                    <input type="hidden" name="source_zone" value="{{ $stock->zone }}">
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-2">Vers *</label>
                    <select name="destination_zone" required class="w-full px-4 py-2 border rounded-lg">
                        <option value="">Sélectionnez une zone</option>
                        @foreach($zones as $zone)
                        <option value="{{ $zone->zone }}" {{ $zone->zone == $stock->zone ? 'disabled' : '' }}>
                            {{ $zone->zone }} ({{ number_format($zone->stock_actuel) }} {{ $zone->unite }})
                        </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-2">Quantité à transférer *</label>
                    <input type="number" step="0.01" name="quantite" required max="{{ $stock->stock_actuel }}" class="w-full px-4 py-2 border rounded-lg">
                    <p class="text-xs text-gray-500 mt-1">Maximum: {{ number_format($stock->stock_actuel) }} {{ $stock->unite }}</p>
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-2">Motif du transfert</label>
                    <textarea name="notes" rows="2" class="w-full px-4 py-2 border rounded-lg" placeholder="Raison du transfert..."></textarea>
                </div>
            </div>
            <div class="mt-6 flex justify-end gap-3">
                <button type="button" onclick="closeTransferModal()" class="px-4 py-2 border rounded-lg">Annuler</button>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">Transférer</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModal(type) {
        const modal = document.getElementById('stockModal');
        const title = document.getElementById('modalTitle');
        const form = document.getElementById('stockForm');
        const motifSelect = document.getElementById('modalMotif');
        const submitBtn = document.getElementById('modalSubmitBtn');
        
        if (type === 'entree') {
            title.textContent = '➕ Ajouter du stock';
            form.action = "{{ route('admin.intrants.ajouter-stock', ['id' => $stock->intrant_id, 'zone' => $stock->zone]) }}";
            motifSelect.innerHTML = '<option value="">Sélectionnez</option><option value="Achat">Achat</option><option value="Réapprovisionnement">Réapprovisionnement</option><option value="Don">Don</option><option value="Transfert">Transfert</option><option value="Inventaire">Inventaire</option>';
            submitBtn.className = 'bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600';
            submitBtn.textContent = 'Ajouter';
        } else {
            title.textContent = '➖ Retirer du stock';
            form.action = "{{ route('admin.intrants.retirer-stock', ['id' => $stock->intrant_id, 'zone' => $stock->zone]) }}";
            motifSelect.innerHTML = '<option value="">Sélectionnez</option><option value="Utilisation">Utilisation terrain</option><option value="Distribution">Distribution producteurs</option><option value="Péremption">Péremption</option><option value="Perte">Perte</option><option value="Transfert">Transfert</option>';
            submitBtn.className = 'bg-orange-500 text-white px-4 py-2 rounded-lg hover:bg-orange-600';
            submitBtn.textContent = 'Retirer';
        }
        
        document.getElementById('modalQuantite').value = '';
        document.getElementById('modalQuantite').max = type === 'sortie' ? {{ $stock->stock_actuel }} : '';
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
    
    function closeModal() {
        const modal = document.getElementById('stockModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
    
    function openTransferModal() {
        const modal = document.getElementById('transferModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
    
    function closeTransferModal() {
        const modal = document.getElementById('transferModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
    
    // Filtres
    const filterType = document.getElementById('filterType');
    const filterMotif = document.getElementById('filterMotif');
    const filterDate = document.getElementById('filterDate');
    const rows = document.querySelectorAll('#mouvementsTable tbody tr');
    
    function filterTable() {
        const type = filterType.value;
        const motif = filterMotif.value;
        const date = filterDate.value;
        
        rows.forEach(row => {
            let show = true;
            if (type !== 'all' && row.dataset.type !== type) show = false;
            if (motif !== 'all' && row.dataset.motif !== motif) show = false;
            if (date && row.dataset.date !== date) show = false;
            row.style.display = show ? '' : 'none';
        });
    }
    
    filterType.addEventListener('change', filterTable);
    filterMotif.addEventListener('change', filterTable);
    filterDate.addEventListener('change', filterTable);
</script>
@endsection