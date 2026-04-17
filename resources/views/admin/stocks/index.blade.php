@extends('layouts.admin')

@section('title', 'Gestion des stocks')
@section('header', 'Gestion des stocks')

@section('content')
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b flex justify-between items-center flex-wrap gap-4">
        <h2 class="text-xl font-semibold">État des stocks</h2>
        <div class="flex space-x-3">
            <a href="{{ route('admin.stocks.export') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
                <i class="fas fa-download mr-2"></i>Exporter
            </a>
            <a href="{{ route('admin.stocks.dashboard') }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                <i class="fas fa-chart-line mr-2"></i>Dashboard
            </a>
            <button onclick="openAjoutModal()" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-secondary">
                <i class="fas fa-plus mr-2"></i>Ajouter du stock
            </button>
        </div>
    </div>
    
    <!-- Filtres -->
    <div class="p-6 border-b bg-gray-50">
        <form method="GET" class="flex flex-wrap gap-4">
            <input type="text" name="produit" placeholder="Rechercher un produit" value="{{ request('produit') }}"
                   class="px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            <select name="zone" class="px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                <option value="">Toutes zones</option>
                @foreach($zones as $zone)
                <option value="{{ $zone }}" {{ request('zone') == $zone ? 'selected' : '' }}>{{ $zone }}</option>
                @endforeach
            </select>
            <label class="flex items-center space-x-2">
                <input type="checkbox" name="alerte" value="1" {{ request('alerte') ? 'checked' : '' }}>
                <span class="text-sm">Stocks critiques</span>
            </label>
            <button type="submit" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-secondary">
                <i class="fas fa-search mr-2"></i>Filtrer
            </button>
        </form>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Produit</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Zone</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Entrepôt</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Stock actuel</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Seuil alerte</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Statut</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($stocks as $stock)
                <tr>
                    <td class="px-6 py-4 font-medium">{{ $stock->produit }}</td>
                    <td class="px-6 py-4">{{ $stock->zone }}</td>
                    <td class="px-6 py-4">{{ $stock->entrepot ?? '-' }}</td>
                    <td class="px-6 py-4 font-semibold {{ $stock->stock_actuel <= $stock->seuil_alerte ? 'text-red-600' : 'text-green-600' }}">
                        {{ number_format($stock->stock_actuel) }} {{ $stock->unite }}
                    </td>
                    <td class="px-6 py-4">{{ number_format($stock->seuil_alerte) }} {{ $stock->unite }}</td>
                    <td class="px-6 py-4">
                        @if($stock->stock_actuel <= $stock->seuil_alerte)
                            <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">
                                <i class="fas fa-exclamation-triangle mr-1"></i>Stock critique
                            </span>
                        @else
                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                Normal
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 space-x-2">
                        <button onclick="openRetraitModal('{{ $stock->produit }}', '{{ $stock->zone }}', {{ $stock->stock_actuel }})" class="text-orange-600">
                            <i class="fas fa-minus-circle"></i>
                        </button>
                        <button onclick="openSeuilModal({{ $stock->id }}, {{ $stock->seuil_alerte }})" class="text-blue-600">
                            <i class="fas fa-chart-line"></i>
                        </button>
                        <a href="{{ route('admin.stocks.mouvements', $stock) }}" class="text-green-600">
                            <i class="fas fa-history"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="p-6">
        {{ $stocks->links() }}
    </div>
</div>

<!-- Modal Ajout Stock -->
<div id="ajoutModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl p-6 max-w-md w-full">
        <h3 class="text-xl font-bold mb-4">Ajouter du stock</h3>
        <form action="{{ route('admin.stocks.store') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold mb-1">Produit *</label>
                    <input type="text" name="produit" required class="w-full px-3 py-2 border rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1">Zone *</label>
                    <input type="text" name="zone" required class="w-full px-3 py-2 border rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1">Entrepôt</label>
                    <input type="text" name="entrepot" class="w-full px-3 py-2 border rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1">Quantité *</label>
                    <input type="number" step="0.01" name="quantite" required class="w-full px-3 py-2 border rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1">Unité *</label>
                    <select name="unite" required class="w-full px-3 py-2 border rounded-lg">
                        <option value="kg">Kilogramme (kg)</option>
                        <option value="tonne">Tonne (t)</option>
                        <option value="sac">Sac</option>
                    </select>
                </div>
            </div>
            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" onclick="closeAjoutModal()" class="px-4 py-2 border rounded-lg">Annuler</button>
                <button type="submit" class="bg-primary text-white px-4 py-2 rounded-lg">Ajouter</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Retrait Stock -->
<div id="retraitModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl p-6 max-w-md w-full">
        <h3 class="text-xl font-bold mb-4">Retirer du stock</h3>
        <form action="{{ route('admin.stocks.sortie') }}" method="POST">
            @csrf
            <input type="hidden" name="produit" id="retrait_produit">
            <input type="hidden" name="zone" id="retrait_zone">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold mb-1">Quantité à retirer *</label>
                    <input type="number" step="0.01" name="quantite" required class="w-full px-3 py-2 border rounded-lg">
                    <p id="stockMax" class="text-xs text-gray-500 mt-1"></p>
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1">Motif *</label>
                    <textarea name="motif" required rows="2" class="w-full px-3 py-2 border rounded-lg" placeholder="Ex: Vente, Distribution, Perte..."></textarea>
                </div>
            </div>
            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" onclick="closeRetraitModal()" class="px-4 py-2 border rounded-lg">Annuler</button>
                <button type="submit" class="bg-orange-500 text-white px-4 py-2 rounded-lg">Retirer</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openAjoutModal() {
        document.getElementById('ajoutModal').classList.remove('hidden');
        document.getElementById('ajoutModal').classList.add('flex');
    }
    function closeAjoutModal() {
        document.getElementById('ajoutModal').classList.add('hidden');
        document.getElementById('ajoutModal').classList.remove('flex');
    }
    
    function openRetraitModal(produit, zone, stock) {
        document.getElementById('retrait_produit').value = produit;
        document.getElementById('retrait_zone').value = zone;
        document.getElementById('stockMax').textContent = 'Stock maximum disponible : ' + stock.toLocaleString() + ' kg';
        document.getElementById('retraitModal').classList.remove('hidden');
        document.getElementById('retraitModal').classList.add('flex');
    }
    function closeRetraitModal() {
        document.getElementById('retraitModal').classList.add('hidden');
        document.getElementById('retraitModal').classList.remove('flex');
    }
    
    function openSeuilModal(id, seuil) {
        const newSeuil = prompt('Nouveau seuil d\'alerte (en ' + (seuil > 100 ? 'kg' : 'unité') + ') :', seuil);
        if (newSeuil !== null) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/admin/stocks/' + id + '/seuil';
            form.innerHTML = '@csrf <input type="hidden" name="seuil_alerte" value="' + newSeuil + '">';
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>
@endsection