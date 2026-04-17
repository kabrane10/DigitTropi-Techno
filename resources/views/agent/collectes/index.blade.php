@extends('layouts.agent')

@section('title', 'Mes collectes')
@section('header', 'Gestion des collectes')

@section('content')
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b flex justify-between items-center flex-wrap gap-4">
        <h2 class="text-xl font-semibold">Liste de mes collectes</h2>
        <a href="{{ route('agent.collectes.create') }}" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-secondary">
            <i class="fas fa-plus mr-2"></i>Nouvelle collecte
        </a>
    </div>
    
    <div class="p-6 border-b bg-gray-50">
        <form method="GET" class="flex flex-wrap gap-4">
            <select name="produit" class="px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                <option value="">Tous les produits</option>
                @foreach($produits as $p)
                <option value="{{ $p }}" {{ request('produit') == $p ? 'selected' : '' }}>{{ $p }}</option>
                @endforeach
            </select>
            <button type="submit" class="bg-primary text-white px-4 py-2 rounded-lg">Filtrer</button>
        </form>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs">Code</th>
                    <th class="px-6 py-3 text-left text-xs">Date</th>
                    <th class="px-6 py-3 text-left text-xs">Producteur</th>
                    <th class="px-6 py-3 text-left text-xs">Produit</th>
                    <th class="px-6 py-3 text-right text-xs">Quantité</th>
                    <th class="px-6 py-3 text-right text-xs">Montant</th>
                    <th class="px-6 py-3 text-left text-xs">Paiement</th>
                    <th class="px-6 py-3 text-center text-xs">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($collectes as $collecte)
                <tr>
                    <td class="px-6 py-4 text-sm">{{ $collecte->code_collecte }}</td>
                    <td class="px-6 py-4 text-sm">{{ $collecte->date_collecte->format('d/m/Y') }}</td>
                    <td class="px-6 py-4">{{ $collecte->producteur->nom_complet }}</td>
                    <td class="px-6 py-4 text-sm">{{ $collecte->produit }}</td>
                    <td class="px-6 py-4 text-right text-sm">{{ number_format($collecte->quantite_nette) }} kg</td>
                    <td class="px-6 py-4 text-right text-sm">{{ number_format($collecte->montant_total, 0, ',', ' ') }} CFA</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full 
                            @if($collecte->statut_paiement == 'paye') bg-green-100 text-green-800
                            @elseif($collecte->statut_paiement == 'partiel') bg-yellow-100 text-yellow-800
                            @else bg-red-100 text-red-800
                            @endif">
                            {{ $collecte->statut_paiement }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center space-x-2">
                        <a href="{{ route('agent.collectes.show', $collecte) }}" class="text-blue-600"><i class="fas fa-eye"></i></a>
                        <a href="{{ route('agent.collectes.edit', $collecte) }}" class="text-green-600"><i class="fas fa-edit"></i></a>
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