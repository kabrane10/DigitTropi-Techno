@extends('layouts.controleur')

@section('title', 'Stocks')
@section('header', 'État des stocks')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-primary">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Stock total</p>
                <p class="text-3xl font-bold">{{ number_format($stats['total_stock']) }} kg</p>
            </div>
            <i class="fas fa-boxes text-primary text-3xl opacity-50"></i>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-red-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Alertes stock</p>
                <p class="text-3xl font-bold text-red-600">{{ number_format($stats['alertes']) }}</p>
            </div>
            <i class="fas fa-exclamation-triangle text-red-500 text-3xl opacity-50"></i>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-green-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Produits en stock</p>
                <p class="text-3xl font-bold">{{ number_format($stats['nb_produits']) }}</p>
            </div>
            <i class="fas fa-seedling text-green-500 text-3xl opacity-50"></i>
        </div>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b">
        <h2 class="text-xl font-semibold">Liste des stocks</h2>
    </div>
    
    <div class="p-6 border-b bg-gray-50">
        <form method="GET" class="flex flex-wrap gap-4">
            <input type="text" name="produit" placeholder="Rechercher..." value="{{ request('produit') }}" class="px-4 py-2 border rounded-lg">
            <select name="zone" class="px-4 py-2 border rounded-lg">
                <option value="">Toutes zones</option>
                @foreach($zones as $zone)
                <option value="{{ $zone }}" {{ request('zone') == $zone ? 'selected' : '' }}>{{ $zone }}</option>
                @endforeach
            </select>
            <button type="submit" class="bg-primary text-white px-4 py-2 rounded-lg">Filtrer</button>
        </form>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr><th>Produit</th><th>Zone</th><th>Stock actuel</th><th>Seuil alerte</th><th>Statut</th><th>Actions</th></tr>
            </thead>
            <tbody class="divide-y">
                @foreach($stocks as $stock)
                <tr>
                    <td class="px-6 py-4 font-medium">{{ $stock->produit }}</td>
                    <td class="px-6 py-4">{{ $stock->zone }}</td>
                    <td class="px-6 py-4 {{ $stock->stock_actuel <= $stock->seuil_alerte ? 'text-red-600 font-bold' : 'text-green-600' }}">{{ number_format($stock->stock_actuel) }} {{ $stock->unite }}</td>
                    <td class="px-6 py-4">{{ number_format($stock->seuil_alerte) }} {{ $stock->unite }}</td>
                    <td class="px-6 py-4">@if($stock->stock_actuel <= $stock->seuil_alerte)<span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Critique</span>@else<span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Normal</span>@endif</td>
                    <td class="px-6 py-4"><a href="{{ route('controleur.stocks.show', $stock) }}" class="text-blue-600"><i class="fas fa-eye"></i></a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="p-6">{{ $stocks->links() }}</div>
</div>
@endsection