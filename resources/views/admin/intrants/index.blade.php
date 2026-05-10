@extends('layouts.admin')

@section('title', 'Gestion des intrants')
@section('header', 'Gestion des intrants')

@section('content')
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b flex justify-between items-center flex-wrap gap-4">
        <h2 class="text-xl font-semibold">Liste des intrants</h2>
        <div class="flex space-x-3">
            <a href="{{ route('admin.intrants.dashboard') }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                <i class="fas fa-chart-line mr-2"></i>Dashboard
            </a>
            <a href="{{ route('admin.intrants.alertes') }}" class="bg-orange-500 text-white px-4 py-2 rounded-lg hover:bg-orange-600">
                <i class="fas fa-exclamation-triangle mr-2"></i>Alertes
            </a>
            <a href="{{ route('admin.intrants.create') }}" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-secondary">
                <i class="fas fa-plus mr-2"></i>Nouvel intrant
            </a>
        </div>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Code</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Nom</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Type</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500">Prix unitaire</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500">Stock par zone</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Statut</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($intrants as $intrant)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm font-mono">{{ $intrant->code_intrant }}</td>
                    <td class="px-6 py-4 font-medium">{{ $intrant->nom }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full 
                            @if($intrant->type == 'engrais') bg-green-100 text-green-800
                            @elseif($intrant->type == 'pesticide') bg-red-100 text-red-800
                            @elseif($intrant->type == 'herbicide') bg-yellow-100 text-yellow-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ $intrant->type_label }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">{{ number_format($intrant->prix_unitaire, 0, ',', ' ') }} CFA/{{ $intrant->unite }}</td>
                    <td class="px-6 py-4">
                        <div class="flex justify-center gap-2">
                            @foreach($intrant->stocks as $stock)
                            <div class="text-center {{ $stock->est_critique ? 'bg-red-50' : 'bg-green-50' }} px-2 py-1 rounded-lg min-w-[60px]">
                                <div class="text-xs font-semibold">{{ $stock->zone }}</div>
                                <div class="text-sm font-bold {{ $stock->est_critique ? 'text-red-600' : 'text-green-600' }}">
                                    {{ number_format($stock->stock_actuel) }}
                                </div>
                                <div class="text-xs text-gray-500">{{ $stock->unite }}</div>
                            </div>
                            @endforeach
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full {{ $intrant->est_actif ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $intrant->est_actif ? 'Actif' : 'Inactif' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center space-x-2">
                        <a href="{{ route('admin.intrants.show', $intrant) }}" class="text-blue-600 hover:text-blue-800"><i class="fas fa-eye"></i></a>
                        <a href="{{ route('admin.intrants.edit', $intrant) }}" class="text-green-600 hover:text-green-800"><i class="fas fa-edit"></i></a>
                        <form action="{{ route('admin.intrants.destroy', $intrant) }}" method="POST" class="inline delete-confirm">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-6 py-8 text-center text-gray-500">Aucun intrant trouvé</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-6">{{ $intrants->links() }}</div>
</div>
@endsection