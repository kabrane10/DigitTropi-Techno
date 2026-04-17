@extends('layouts.admin')

@section('title', 'Liste des suivis')
@section('header', 'Liste des suivis parcellaires')

@section('content')
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b flex justify-between items-center flex-wrap gap-4">
        <h2 class="text-xl font-semibold">Suivi des exploitations</h2>
        <div class="flex space-x-3">
            <a href="{{ route('admin.suivi.export') }}" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600">
                <i class="fas fa-file-excel mr-2"></i>Exporter
            </a>
            <a href="{{ route('admin.suivi.create') }}" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-secondary">
                <i class="fas fa-plus mr-2"></i>Nouveau suivi
            </a>
        </div>
    </div>
    
    <!-- Filtres -->
    <div class="p-6 border-b bg-gray-50">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <select name="producteur_id" class="px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                <option value="">Tous les producteurs</option>
                @foreach($producteurs as $producteur)
                <option value="{{ $producteur->id }}" {{ request('producteur_id') == $producteur->id ? 'selected' : '' }}>
                    {{ $producteur->nom_complet }}
                </option>
                @endforeach
            </select>
            <select name="animateur_id" class="px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                <option value="">Tous les animateurs</option>
                @foreach($animateurs as $animateur)
                <option value="{{ $animateur->id }}" {{ request('animateur_id') == $animateur->id ? 'selected' : '' }}>
                    {{ $animateur->nom_complet }}
                </option>
                @endforeach
            </select>
            <select name="sante" class="px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                <option value="">Tous les états</option>
                <option value="excellente" {{ request('sante') == 'excellente' ? 'selected' : '' }}>🌟 Excellente</option>
                <option value="bonne" {{ request('sante') == 'bonne' ? 'selected' : '' }}>✅ Bonne</option>
                <option value="moyenne" {{ request('sante') == 'moyenne' ? 'selected' : '' }}>⚠️ Moyenne</option>
                <option value="mauvaise" {{ request('sante') == 'mauvaise' ? 'selected' : '' }}>❌ Mauvaise</option>
                <option value="critique" {{ request('sante') == 'critique' ? 'selected' : '' }}>🔴 Critique</option>
            </select>
            <button type="submit" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-secondary">
                <i class="fas fa-search mr-2"></i>Filtrer
            </button>
        </form>
    </div>
    
    <!-- Tableau des suivis -->
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Code</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Producteur</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Animateur</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Superficie</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Stade</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Santé</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($suivis as $suivi)
                <tr>
                    <td class="px-6 py-4 text-sm font-mono">{{ $suivi->code_suivi }}</td>
                    <td class="px-6 py-4 text-sm">{{ $suivi->date_suivi->format('d/m/Y') }}</td>
                    <td class="px-6 py-4">{{ $suivi->producteur->nom_complet }}</td>
                    <td class="px-6 py-4 text-sm">{{ $suivi->animateur->nom_complet }}</td>
                    <td class="px-6 py-4 text-sm">{{ number_format($suivi->superficie_actuelle, 2) }} ha</td>
                    <td class="px-6 py-4 text-sm">{{ $suivi->stade_croissance }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full 
                            @if($suivi->sante_cultures == 'excellente') bg-green-100 text-green-800
                            @elseif($suivi->sante_cultures == 'bonne') bg-blue-100 text-blue-800
                            @elseif($suivi->sante_cultures == 'moyenne') bg-yellow-100 text-yellow-800
                            @elseif($suivi->sante_cultures == 'mauvaise') bg-orange-100 text-orange-800
                            @else bg-red-100 text-red-800
                            @endif">
                            {{ $suivi->sante_cultures }}
                        </span>
                    </td>
                    <td class="px-6 py-4 space-x-2">
                        <a href="{{ route('admin.suivi.show', $suivi) }}" class="text-blue-600 hover:text-blue-800">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('admin.suivi.edit', $suivi) }}" class="text-green-600 hover:text-green-800">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.suivi.destroy', $suivi) }}" method="POST" class="inline delete-confirm">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                        <i class="fas fa-clipboard-list text-4xl mb-2 opacity-50"></i>
                        <p>Aucun suivi trouvé</p>
                        <a href="{{ route('admin.suivi.create') }}" class="inline-block mt-2 text-primary hover:underline">
                            + Ajouter un suivi
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="p-6">
        {{ $suivis->links() }}
    </div>
</div>
@endsection