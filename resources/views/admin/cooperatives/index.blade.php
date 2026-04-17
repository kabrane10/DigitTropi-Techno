@extends('layouts.admin')

@section('title', 'Coopératives')
@section('header', 'Gestion des coopératives')

@section('content')
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b flex justify-between items-center flex-wrap gap-4">
        <h2 class="text-xl font-semibold">Liste des coopératives</h2>
        <div class="flex space-x-3">
            <a href="{{ route('admin.cooperatives.export') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
                <i class="fas fa-download mr-2"></i>Exporter
            </a>
            <a href="{{ route('admin.cooperatives.create') }}" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-secondary">
                <i class="fas fa-plus mr-2"></i>Nouvelle coopérative
            </a>
        </div>
    </div>
    
    <!-- Filtres -->
    <div class="p-6 border-b bg-gray-50">
        <form method="GET" class="flex flex-wrap gap-4">
            <select name="region" class="px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                <option value="">Toutes régions</option>
                <option value="Centrale" {{ request('region') == 'Centrale' ? 'selected' : '' }}>Centrale</option>
                <option value="Kara" {{ request('region') == 'Kara' ? 'selected' : '' }}>Kara</option>
                <option value="Savanes" {{ request('region') == 'Savanes' ? 'selected' : '' }}>Savanes</option>
            </select>
            <select name="statut" class="px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                <option value="">Tous statuts</option>
                <option value="active" {{ request('statut') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="suspendue" {{ request('statut') == 'suspendue' ? 'selected' : '' }}>Suspendue</option>
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
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Contact</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Région</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Membres</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Statut</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($cooperatives as $cooperative)
                <tr>
                    <td class="px-6 py-4 text-sm">{{ $cooperative->code_cooperative }}</td>
                    <td class="px-6 py-4 font-medium">{{ $cooperative->nom }}</td>
                    <td class="px-6 py-4 text-sm">{{ $cooperative->contact }}</td>
                    <td class="px-6 py-4 text-sm">{{ $cooperative->region }}</td>
                    <td class="px-6 py-4 text-sm">{{ number_format($cooperative->nombre_membres) }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full {{ $cooperative->statut == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $cooperative->statut }}
                        </span>
                    </td>
                    <td class="px-6 py-4 space-x-2">
                        <a href="{{ route('admin.cooperatives.show', $cooperative) }}" class="text-blue-600"><i class="fas fa-eye"></i></a>
                        <a href="{{ route('admin.cooperatives.edit', $cooperative) }}" class="text-green-600"><i class="fas fa-edit"></i></a>
                        <form action="{{ route('admin.cooperatives.destroy', $cooperative) }}" method="POST" class="inline delete-confirm">
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
        {{ $cooperatives->links() }}
    </div>
</div>
@endsection