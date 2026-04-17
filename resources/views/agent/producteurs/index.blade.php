@extends('layouts.agent')

@section('title', 'Mes producteurs')
@section('header', 'Gestion des producteurs')

@section('content')
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b flex justify-between items-center flex-wrap gap-4">
        <h2 class="text-xl font-semibold">Liste de mes producteurs</h2>
        <a href="{{ route('agent.producteurs.create') }}" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-secondary">
            <i class="fas fa-plus mr-2"></i>Nouveau producteur
        </a>
    </div>
    
    <div class="p-6 border-b bg-gray-50">
        <form method="GET" class="flex flex-wrap gap-4">
            <input type="text" name="search" placeholder="Rechercher..." value="{{ request('search') }}"
                   class="px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
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
            <button type="submit" class="bg-primary text-white px-4 py-2 rounded-lg">Filtrer</button>
        </form>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs">Code</th>
                    <th class="px-6 py-3 text-left text-xs">Nom</th>
                    <th class="px-6 py-3 text-left text-xs">Contact</th>
                    <th class="px-6 py-3 text-left text-xs">Région</th>
                    <th class="px-6 py-3 text-left text-xs">Culture</th>
                    <th class="px-6 py-3 text-right text-xs">Superficie</th>
                    <th class="px-6 py-3 text-left text-xs">Statut</th>
                    <th class="px-6 py-3 text-center text-xs">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($producteurs as $producteur)
                <tr>
                    <td class="px-6 py-4 text-sm">{{ $producteur->code_producteur }}</td>
                    <td class="px-6 py-4 font-medium">{{ $producteur->nom_complet }}</td>
                    <td class="px-6 py-4 text-sm">{{ $producteur->contact }}</td>
                    <td class="px-6 py-4 text-sm">{{ $producteur->region }}</td>
                    <td class="px-6 py-4 text-sm">{{ $producteur->culture_pratiquee }}</td>
                    <td class="px-6 py-4 text-right text-sm">{{ number_format($producteur->superficie_totale, 2) }} ha</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full {{ $producteur->statut == 'actif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $producteur->statut }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center space-x-2">
                        <a href="{{ route('agent.producteurs.show', $producteur) }}" class="text-blue-600"><i class="fas fa-eye"></i></a>
                        <a href="{{ route('agent.producteurs.edit', $producteur) }}" class="text-green-600"><i class="fas fa-edit"></i></a>
                        <form action="{{ route('agent.producteurs.destroy', $producteur) }}" method="POST" class="inline delete-confirm">
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
        {{ $producteurs->links() }}
    </div>
</div>
@endsection