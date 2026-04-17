@extends('layouts.admin')

@section('title', 'Collectes')
@section('header', 'Gestion des collectes')

@section('content')
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b flex justify-between items-center flex-wrap gap-4">
        <h2 class="text-xl font-semibold">Liste des collectes</h2>
        <div class="flex space-x-3">
            <a href="{{ route('admin.collectes.dashboard') }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                <i class="fas fa-chart-line mr-2"></i>Dashboard
            </a>
            <a href="{{ route('admin.collectes.create') }}" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-secondary">
                <i class="fas fa-plus mr-2"></i>Nouvelle collecte
            </a>
              <a href="{{ route('admin.rapports.export-collectes', request()->all()) }}" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600">
      <i class="fas fa-file-excel mr-2"></i>Exporter Excel
  </a>
        </div>
    </div>
    
    <!-- Filtres -->
    <div class="p-6 border-b bg-gray-50">
        <form method="GET" class="flex flex-wrap gap-4">
            <select name="produit" class="px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                <option value="">Tous les produits</option>
                @foreach($produits as $produit)
                <option value="{{ $produit }}" {{ request('produit') == $produit ? 'selected' : '' }}>{{ $produit }}</option>
                @endforeach
            </select>
            <input type="date" name="date_debut" value="{{ request('date_debut') }}" placeholder="Date début"
                   class="px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            <input type="date" name="date_fin" value="{{ request('date_fin') }}" placeholder="Date fin"
                   class="px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
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
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Producteur</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Produit</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Quantité</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Montant</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Paiement</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($collectes as $collecte)
                <tr>
                    <td class="px-6 py-4 text-sm">{{ $collecte->code_collecte }}</td>
                    <td class="px-6 py-4 text-sm">{{ $collecte->date_collecte->format('d/m/Y') }}</td>
                    <td class="px-6 py-4">{{ $collecte->producteur->nom_complet }}</td>
                    <td class="px-6 py-4 text-sm">{{ $collecte->produit }}</td>
                    <td class="px-6 py-4 text-sm">{{ number_format($collecte->quantite_nette) }} kg</td>
                    <td class="px-6 py-4 text-sm">{{ number_format($collecte->montant_total, 0, ',', ' ') }} CFA</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full 
                            @if($collecte->statut_paiement == 'paye') bg-green-100 text-green-800
                            @elseif($collecte->statut_paiement == 'partiel') bg-yellow-100 text-yellow-800
                            @else bg-red-100 text-red-800
                            @endif">
                            {{ $collecte->statut_paiement }}
                        </span>
                    </td>
                    <td class="px-6 py-4 space-x-2">
                        <a href="{{ route('admin.collectes.show', $collecte) }}" class="text-blue-600"><i class="fas fa-eye"></i></a>
                        <form action="{{ route('admin.collectes.destroy', $collecte) }}" method="POST" class="inline delete-confirm">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="p-6">
        {{ $collectes->links() }}
    </div>
</div>
@endsection