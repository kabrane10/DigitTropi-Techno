@extends('layouts.admin')

@section('title', 'Estimations de besoins')
@section('header', 'Gestion des estimations de besoins')

@section('content')
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b flex justify-between items-center flex-wrap gap-4">
        <h2 class="text-xl font-semibold">Liste des estimations</h2>
        <a href="{{ route('admin.estimations.create') }}" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-secondary">
            <i class="fas fa-plus mr-2"></i>Nouvelle estimation
        </a>
    </div>
    
    <!-- Filtres -->
    <div class="p-6 border-b bg-gray-50">
        <form method="GET" class="flex flex-wrap gap-4">
            <select name="statut" class="px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                <option value="">Tous statuts</option>
                <option value="en_attente" {{ request('statut') == 'en_attente' ? 'selected' : '' }}>⏳ En attente</option>
                <option value="approuve" {{ request('statut') == 'approuve' ? 'selected' : '' }}>✅ Approuvé</option>
                <option value="rejete" {{ request('statut') == 'rejete' ? 'selected' : '' }}>❌ Rejeté</option>
            </select>
            <select name="producteur_id" class="px-4 py-2 border rounded-lg">
                <option value="">Tous producteurs</option>
                @foreach($producteurs ?? [] as $p)
                <option value="{{ $p->id }}" {{ request('producteur_id') == $p->id ? 'selected' : '' }}>{{ $p->nom_complet }}</option>
                @endforeach
            </select>
            <button type="submit" class="bg-primary text-white px-4 py-2 rounded-lg">
                <i class="fas fa-search mr-2"></i>Filtrer
            </button>
        </form>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left">Code</th>
                    <th class="px-6 py-3 text-left">Date</th>
                    <th class="px-6 py-3 text-left">Producteur</th>
                    <th class="px-6 py-3 text-left">Semence</th>
                    <th class="px-6 py-3 text-right">Quantité</th>
                    <th class="px-6 py-3 text-right">Superficie</th>
                    <th class="px-6 py-3 text-right">Crédit estimé</th>
                    <th class="px-6 py-3 text-left">Statut</th>
                    <th class="px-6 py-3 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($estimations as $estimation)
                <tr>
                    <td class="px-6 py-4 text-sm font-mono">{{ $estimation->code_estimation }}</td>
                    <td class="px-6 py-4 text-sm">{{ $estimation->date_estimation->format('d/m/Y') }}</td>
                    <td class="px-6 py-4">{{ $estimation->producteur->nom_complet }}</td>
                    <td class="px-6 py-4">{{ $estimation->semence->nom }} ({{ $estimation->semence->variete }})</td>
                    <td class="px-6 py-4 text-right">{{ number_format($estimation->quantite_estimee) }} {{ $estimation->semence->unite }}</td>
                    <td class="px-6 py-4 text-right">{{ number_format($estimation->superficie_prevue, 2) }} ha</td>
                    <td class="px-6 py-4 text-right">{{ number_format($estimation->credit_montant ?? 0, 0, ',', ' ') }} CFA</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full 
                            @if($estimation->statut == 'approuve') bg-green-100 text-green-800
                            @elseif($estimation->statut == 'rejete') bg-red-100 text-red-800
                            @else bg-yellow-100 text-yellow-800
                            @endif">
                            {{ $estimation->statut_label }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center space-x-2">
                        <a href="{{ route('admin.estimations.show', $estimation) }}" class="text-blue-600"><i class="fas fa-eye"></i></a>
                        <a href="{{ route('admin.estimations.edit', $estimation) }}" class="text-green-600"><i class="fas fa-edit"></i></a>
                        <form action="{{ route('admin.estimations.destroy', $estimation) }}" method="POST" class="inline delete-confirm">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="9" class="px-6 py-8 text-center text-gray-500">Aucune estimation</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-6">{{ $estimations->links() }}</div>
</div>
@endsection