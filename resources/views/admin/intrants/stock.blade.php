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
                                {{ $mvt->type == 'entree' ? ' Entrée' : ' Sortie' }}
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
                    <input type="number" step="0.01" name="quantite" id="modalQuantite" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary/50 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-2">Motif *</label>
                    <select name="motif" id="modalMotif" required onchange="generateStockRef()" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary/50 outline-none">
                        <option value="">Sélectionnez</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-2">Référence</label>
                    <input type="text" name="reference" id="modalReference" required readonly 
                           class="w-full px-4 py-2 border rounded-lg bg-gray-50 font-mono text-sm text-gray-700" 
                           placeholder="Génération automatique...">
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-2">Notes</label>
                    <textarea name="notes" rows="2" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary/50 outline-none"></textarea>
                </div>
            </div>
            <div class="mt-6 flex justify-end gap-3">
                <button type="button" onclick="closeModal()" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition">Annuler</button>
                <button type="submit" class="px-4 py-2 rounded-lg text-white bg-primary hover:bg-secondary transition shadow-sm" id="modalSubmitBtn">Confirmer</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Transférer -->
<div id="transferModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center">
    <div class="bg-white rounded-xl p-6 max-w-md w-full">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold">Transférer du stock</h3>
            <button onclick="closeTransferModal()" class="text-gray-400 hover:text-gray-600">&times;</button>
        </div>
        <form action="{{ route('admin.intrants.transferer', ['intrant' => $stock->intrant_id]) }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold mb-2">De *</label>
                        <input type="text" value="{{ $stock->zone }}" class="w-full px-4 py-2 border rounded-lg bg-gray-100" disabled>
                        <input type="hidden" name="source_zone" value="{{ $stock->zone }}">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-2">Vers *</label>
                        <select name="destination_zone" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500/50 outline-none">
                            <option value="">Sélectionnez</option>
                            @foreach($zones as $zone)
                            <option value="{{ $zone->zone }}" {{ $zone->zone == $stock->zone ? 'disabled' : '' }}>
                                {{ $zone->zone }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-2">Quantité à transférer *</label>
                    <input type="number" step="0.01" name="quantite" required max="{{ $stock->stock_actuel }}" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500/50 outline-none">
                    <p class="text-xs text-gray-500 mt-1">Maximum disponible: {{ number_format($stock->stock_actuel) }} {{ $stock->unite }}</p>
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-2">Référence du transfert</label>
                    <input type="text" name="reference" id="transferReference" required readonly 
                           class="w-full px-4 py-2 border rounded-lg bg-gray-50 font-mono text-sm text-gray-700" 
                           placeholder="Génération automatique...">
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-2">Motif du transfert</label>
                    <textarea name="notes" rows="2" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500/50 outline-none" placeholder="Raison du transfert..."></textarea>
                </div>
            </div>
            <div class="mt-6 flex justify-end gap-3">
                <button type="button" onclick="closeTransferModal()" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition">Annuler</button>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition shadow-sm">Transférer</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Initialisation des Modals

    function openModal(type) {
        const modal = document.getElementById('stockModal');
        const title = document.getElementById('modalTitle');
        const form = document.getElementById('stockForm');
        const motifSelect = document.getElementById('modalMotif');
        const submitBtn = document.getElementById('modalSubmitBtn');
        
        if (type === 'entree') {
            title.textContent = ' Ajouter du stock';
            form.action = "{{ route('admin.intrants.ajouter-stock', ['id' => $stock->intrant_id, 'zone' => $stock->zone]) }}";
            motifSelect.innerHTML = '<option value="">Sélectionnez</option><option value="Achat">Achat</option><option value="Réapprovisionnement">Réapprovisionnement</option><option value="Don">Don</option><option value="Transfert">Transfert</option><option value="Inventaire">Inventaire</option>';
            submitBtn.className = 'bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition shadow-sm';
            submitBtn.textContent = 'Ajouter';
        } else {
            title.textContent = ' Retirer du stock';
            form.action = "{{ route('admin.intrants.retirer-stock', ['id' => $stock->intrant_id, 'zone' => $stock->zone]) }}";
            motifSelect.innerHTML = '<option value="">Sélectionnez</option><option value="Utilisation">Utilisation terrain</option><option value="Distribution">Distribution producteurs</option><option value="Péremption">Péremption</option><option value="Perte">Perte</option><option value="Transfert">Transfert</option>';
            submitBtn.className = 'bg-orange-500 text-white px-4 py-2 rounded-lg hover:bg-orange-600 transition shadow-sm';
            submitBtn.textContent = 'Retirer';
        }
        
        document.getElementById('modalQuantite').value = '';
        document.getElementById('modalQuantite').max = type === 'sortie' ? {{ $stock->stock_actuel }} : '';
        
        //  GÉNÉRATION AUTOMATIQUE DE LA RÉFÉRENCE 
        generateStockRef(); 

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
        
        // GÉNÉRATION AUTOMATIQUE DE LA RÉFÉRENCE DE TRANSFERT 
        document.getElementById('transferReference').value = generateRef('TRF');
        
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
    
    function closeTransferModal() {
        const modal = document.getElementById('transferModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    // --- Générateurs de Références ---

    function generateRef(prefix) {
        const now = new Date();
        const dateStr = now.getFullYear().toString() + 
                       (now.getMonth() + 1).toString().padStart(2, '0') + 
                       now.getDate().toString().padStart(2, '0');
        const timeStr = now.getHours().toString().padStart(2, '0') + 
                       now.getMinutes().toString().padStart(2, '0') + 
                       now.getSeconds().toString().padStart(2, '0');
        const randomStr = Math.floor(1000 + Math.random() * 9000);
        
        return `${prefix}-${dateStr}-${timeStr}-${randomStr}`;
    }

    function generateStockRef() {
        const motif = document.getElementById('modalMotif').value.toLowerCase();
        let prefix = 'MVT'; // Mouvement par défaut
        
        // On adapte le préfixe selon le contenu du motif si nécessaire
        if (motif.includes('achat') || motif.includes('retour') || motif.includes('don') || motif.includes('réapprovisionnement')) prefix = 'ENT';
        if (motif.includes('vente') || motif.includes('perte') || motif.includes('utilisation') || motif.includes('distribution') || motif.includes('péremption')) prefix = 'SOR';
        
        document.getElementById('modalReference').value = generateRef(prefix);
    }

    // --- Filtres de Table ---

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

    // Ajout des écouteurs d'événements pour les filtres si les éléments existent
    if(filterType) filterType.addEventListener('change', filterTable);
    if(filterMotif) filterMotif.addEventListener('change', filterTable);
    if(filterDate) filterDate.addEventListener('change', filterTable);

</script>
@endsection