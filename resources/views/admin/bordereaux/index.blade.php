@extends('layouts.admin')

@section('title', 'Bordereaux')
@section('header', 'Gestion des bordereaux')

@section('content')
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b flex justify-between items-center flex-wrap gap-4">
        <h2 class="text-xl font-semibold">Liste des bordereaux</h2>
        <div class="flex space-x-3">
            <a href="{{ route('admin.bordereaux.form-chargement') }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                <i class="fas fa-truck mr-2"></i>Chargement
            </a>
            <a href="{{ route('admin.bordereaux.form-livraison') }}" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600">
                <i class="fas fa-boxes mr-2"></i>Livraison
            </a>
        </div>
    </div>
    
    <!-- Filtres -->
    <div class="p-6 border-b bg-gray-50">
        <form method="GET" class="flex flex-wrap gap-4">
            <select name="type" class="px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                <option value="">Tous les types</option>
                <option value="collecte" {{ request('type') == 'collecte' ? 'selected' : '' }}>Collecte</option>
                <option value="achat" {{ request('type') == 'achat' ? 'selected' : '' }}>Achat</option>
                <option value="chargement" {{ request('type') == 'chargement' ? 'selected' : '' }}>Chargement</option>
                <option value="livraison" {{ request('type') == 'livraison' ? 'selected' : '' }}>Livraison</option>
            </select>
            <select name="statut" class="px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                <option value="">Tous statuts</option>
                <option value="valide" {{ request('statut') == 'valide' ? 'selected' : '' }}>Validé</option>
                <option value="annule" {{ request('statut') == 'annule' ? 'selected' : '' }}>Annulé</option>
                <option value="en_attente" {{ request('statut') == 'en_attente' ? 'selected' : '' }}>En attente</option>
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
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Date émission</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Émetteur</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Destinataire</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Statut</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($bordereaux as $bordereau)
                <tr>
                    <td class="px-6 py-4 text-sm font-mono">{{ $bordereau->code_bordereau }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full 
                            @if($bordereau->type == 'collecte') bg-blue-100 text-blue-800
                            @elseif($bordereau->type == 'achat') bg-green-100 text-green-800
                            @elseif($bordereau->type == 'chargement') bg-orange-100 text-orange-800
                            @else bg-purple-100 text-purple-800
                            @endif">
                            {{ $bordereau->type_label }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm">{{ $bordereau->date_emission->format('d/m/Y H:i') }}</td>
                    <td class="px-6 py-4 text-sm">{{ $bordereau->emetteur }}</td>
                    <td class="px-6 py-4 text-sm">{{ $bordereau->destinataire ?? '-' }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full bg-{{ $bordereau->statut_color }}-100 text-{{ $bordereau->statut_color }}-800">
                            {{ $bordereau->statut_label }}
                        </span>
                    </td>
                    <td class="px-6 py-4 space-x-2">
                        <a href="{{ route('admin.bordereaux.show', $bordereau) }}" class="text-blue-600"><i class="fas fa-eye"></i></a>
                        <a href="{{ route('admin.bordereaux.print', $bordereau) }}" target="_blank" class="text-gray-600"><i class="fas fa-print"></i></a>
                        @if($bordereau->statut != 'annule')
                        <form action="{{ route('admin.bordereaux.annuler', $bordereau) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-red-600" onclick="return confirm('Annuler ce bordereau ?')">
                                <i class="fas fa-times-circle"></i>
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="p-6">
        {{ $bordereaux->links() }}
    </div>
</div>
@endsection