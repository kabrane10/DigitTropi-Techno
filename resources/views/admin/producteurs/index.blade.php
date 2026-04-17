@extends('layouts.admin')

@section('title', 'Producteurs')
@section('header', 'Gestion des Producteurs')

@section('content')
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b border-gray-200">
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
            <div class="flex gap-2">
                <a href="{{ route('admin.producteurs.create') }}" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-secondary transition flex items-center gap-2">
                    <i class="fas fa-plus"></i> Nouveau Producteur
                </a>
                <!-- Bouton Export Excel -->
                <a href="{{ route('admin.producteurs.export') }}" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition">
                    <i class="fas fa-file-excel mr-2"></i>Exporter Excel
                </a>
            </div>
            
            <form method="GET" class="flex gap-2">
                <input type="text" name="search" placeholder="Rechercher..." value="{{ request('search') }}" class="px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                <select name="region" class="px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    <option value="">Toutes régions</option>
                    <option value="Centrale" {{ request('region') == 'Centrale' ? 'selected' : '' }}>Centrale</option>
                    <option value="Kara" {{ request('region') == 'Kara' ? 'selected' : '' }}>Kara</option>
                    <option value="Savanes" {{ request('region') == 'Savanes' ? 'selected' : '' }}>Savanes</option>
                </select>
                <select name="statut" class="px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    <option value="">Tous statuts</option>
                    <option value="actif" {{ request('statut') == 'actif' ? 'selected' : '' }}>Actif</option>
                    <option value="inactif" {{ request('statut') == 'inactif' ? 'selected' : '' }}>Inactif</option>
                </select>
                <button type="submit" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-secondary">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Code</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nom</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Contact</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Région</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Culture</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($producteurs as $producteur)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $producteur->code_producteur }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $producteur->nom_complet }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $producteur->contact }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $producteur->region }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $producteur->culture_pratiquee }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full {{ $producteur->statut == 'actif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $producteur->statut }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm space-x-2">
                        <a href="{{ route('admin.producteurs.show', $producteur) }}" class="text-blue-600 hover:text-blue-800">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('admin.producteurs.edit', $producteur) }}" class="text-green-600 hover:text-green-800">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.producteurs.destroy', $producteur) }}" method="POST" class="inline delete-confirm">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="p-6 border-t border-gray-200">
        {{ $producteurs->links() }}
    </div>
</div>
@endsection