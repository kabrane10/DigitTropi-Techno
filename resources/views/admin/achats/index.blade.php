@extends('layouts.admin')

@section('title', 'Bordereaux d\'achat')
@section('header', 'Gestion des achats')

@section('content')
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b flex justify-between items-center flex-wrap gap-4">
        <h2 class="text-xl font-semibold">Liste des achats</h2>
        <a href="{{ route('admin.achats.create') }}" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-secondary">
            <i class="fas fa-plus mr-2"></i>Nouvel achat
        </a>
        <a href="{{ route('admin.achats.dashboard') }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
            <i class="fas fa-chart-line mr-2"></i>Dashboard
        </a>
        <a href="{{ route('admin.rapports.export-achats', request()->all()) }}" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600">
            <i class="fas fa-file-excel mr-2"></i>Exporter Excel
        </a>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left">Code</th>
                    <th class="px-6 py-3 text-left">Date</th>
                    <th class="px-6 py-3 text-left">Producteur</th>
                    <th class="px-6 py-3 text-left">Produit</th>
                    <th class="px-6 py-3 text-right">Quantité</th>
                    <th class="px-6 py-3 text-right">Montant</th>
                    <th class="px-6 py-3 text-left">Acheteur</th>
                    <th class="px-6 py-3 text-left">Statut</th>
                    <th class="px-6 py-3 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($achats as $achat)
                <tr>
                    <td class="px-6 py-4">{{ $achat->code_achat }}</td>
                    <td class="px-6 py-4">{{ $achat->date_achat->format('d/m/Y') }}</td>
                    <td class="px-6 py-4">{{ $achat->collecte->producteur->nom_complet }}</td>
                    <td class="px-6 py-4">{{ $achat->collecte->produit }}</td>
                    <td class="px-6 py-4 text-right">{{ number_format($achat->quantite) }} kg</td>
                    <td class="px-6 py-4 text-right">{{ number_format($achat->montant_total, 0, ',', ' ') }} CFA</td>
                    <td class="px-6 py-4">{{ $achat->acheteur }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full {{ $achat->statut == 'confirme' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ $achat->statut }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center space-x-2">
                        <a href="{{ route('admin.achats.show', $achat) }}" class="text-blue-600"><i class="fas fa-eye"></i></a>
                        <form action="{{ route('admin.bordereaux.generer-achat', $achat->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-green-600" title="Générer bordereau">
                                <i class="fas fa-file-alt"></i>
                            </button>
                        </form>
                        <form action="{{ route('admin.achats.destroy', $achat) }}" method="POST" class="inline delete-confirm">
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
        {{ $achats->links() }}
    </div>
</div>
@endsection