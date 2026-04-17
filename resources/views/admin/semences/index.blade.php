@extends('layouts.admin')

@section('title', 'Semences')
@section('header', 'Gestion des semences')

@section('content')
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b flex justify-between items-center flex-wrap gap-4">
        <h2 class="text-xl font-semibold">Liste des semences</h2>
        <div class="flex space-x-3">
            <a href="{{ route('admin.semences.create') }}" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-secondary">
                <i class="fas fa-plus mr-2"></i>Nouvelle semence
            </a>
        </div>
    </div>
    
    <!-- Filtres -->
    <div class="p-6 border-b bg-gray-50">
        <form method="GET" class="flex flex-wrap gap-4">
            <input type="text" name="search" placeholder="Rechercher..." value="{{ request('search') }}"
                   class="px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            <select name="type" class="px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                <option value="">Tous les types</option>
                <option value="soja" {{ request('type') == 'soja' ? 'selected' : '' }}>Soja</option>
                <option value="arachide" {{ request('type') == 'arachide' ? 'selected' : '' }}>Arachide</option>
                <option value="sesame" {{ request('type') == 'sesame' ? 'selected' : '' }}>Sésame</option>
                <option value="mais" {{ request('type') == 'mais' ? 'selected' : '' }}>Maïs</option>
                <option value="riz" {{ request('type') == 'riz' ? 'selected' : '' }}>Riz</option>
                <option value="gombo" {{ request('type') == 'gombo' ? 'selected' : '' }}>Gombo</option>
                <option value="autres" {{ request('type') == 'autres' ? 'selected' : '' }}>Autres</option>
            </select>
            <button type="submit" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-secondary">
                <i class="fas fa-search mr-2"></i>Filtrer
            </button>
        </form>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Code</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Nom</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Variété</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Type</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500">Prix unitaire</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500">Stock</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($semences as $semence)
                <tr>
                    <td class="px-6 py-4 text-sm font-mono">{{ $semence->code_semence }}</td>
                    <td class="px-6 py-4 font-medium">{{ $semence->nom }}</td>
                    <td class="px-6 py-4 text-sm">{{ $semence->variete }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full 
                            @if($semence->type == 'soja') bg-green-100 text-green-800
                            @elseif($semence->type == 'mais') bg-yellow-100 text-yellow-800
                            @elseif($semence->type == 'riz') bg-blue-100 text-blue-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ ucfirst($semence->type) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">{{ number_format($semence->prix_unitaire, 0, ',', ' ') }} CFA/{{ $semence->unite }}</td>
                    <td class="px-6 py-4 text-right">
                        <span class="font-semibold {{ $semence->stock_disponible <= 100 ? 'text-red-600' : 'text-green-600' }}">
                            {{ number_format($semence->stock_disponible) }} {{ $semence->unite }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center space-x-2">
                        <a href="{{ route('admin.semences.show', $semence) }}" class="text-blue-600"><i class="fas fa-eye"></i></a>
                        <a href="{{ route('admin.semences.edit', $semence) }}" class="text-green-600"><i class="fas fa-edit"></i></a>
                        <button onclick="openAjoutStockModal({{ $semence->id }}, '{{ $semence->nom }}')" class="text-orange-600"><i class="fas fa-plus-circle"></i></button>
                        <form action="{{ route('admin.semences.destroy', $semence) }}" method="POST" class="inline delete-confirm">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                        <i class="fas fa-seedling text-4xl mb-2 opacity-50"></i>
                        <p>Aucune semence trouvée</p>
                        <a href="{{ route('admin.semences.create') }}" class="inline-block mt-2 text-primary hover:underline">+ Ajouter une semence</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="p-6">
        {{ $semences->links() }}
    </div>
</div>

<!-- Modal Ajout Stock -->
<div id="ajoutStockModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl p-6 max-w-md w-full">
        <h3 class="text-xl font-bold mb-4">Ajouter du stock</h3>
        <form id="ajoutStockForm" method="POST" action="">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-semibold mb-2">Quantité à ajouter (kg)</label>
                <input type="number" step="0.01" name="quantite" required class="w-full px-4 py-2 border rounded-lg">
            </div>
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeAjoutStockModal()" class="px-4 py-2 border rounded-lg">Annuler</button>
                <button type="submit" class="bg-primary text-white px-4 py-2 rounded-lg">Ajouter</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openAjoutStockModal(id, nom) {
        const modal = document.getElementById('ajoutStockModal');
        const form = document.getElementById('ajoutStockForm');
        form.action = "/admin/semences/" + id + "/ajouter-stock";
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
    
    function closeAjoutStockModal() {
        const modal = document.getElementById('ajoutStockModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
</script>
@endsection