@extends('layouts.controleur')

@section('title', 'Collectes')
@section('header', 'Liste des collectes')

@section('content')
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b"><h2 class="text-xl font-semibold">Toutes les collectes</h2></div>
    <div class="p-6 border-b bg-gray-50">
        <form method="GET" class="flex flex-wrap gap-4">
            <select name="produit" class="px-4 py-2 border rounded-lg"><option value="">Tous produits</option>@foreach($produits as $p)<option value="{{ $p }}" {{ request('produit') == $p ? 'selected' : '' }}>{{ $p }}</option>@endforeach</select>
            <input type="date" name="date_debut" value="{{ request('date_debut') }}" class="px-4 py-2 border rounded-lg" placeholder="Date début">
            <input type="date" name="date_fin" value="{{ request('date_fin') }}" class="px-4 py-2 border rounded-lg" placeholder="Date fin">
            <button type="submit" class="bg-primary text-white px-4 py-2 rounded-lg">Filtrer</button>
        </form>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50"><tr><th class="px-6 py-3 text-left">Code</th><th class="px-6 py-3 text-left">Date</th><th class="px-6 py-3 text-left">Producteur</th><th class="px-6 py-3 text-left">Produit</th><th class="px-6 py-3 text-right">Quantité</th><th class="px-6 py-3 text-right">Montant</th><th class="px-6 py-3 text-center">Actions</th></tr></thead>
            <tbody class="divide-y">
                @foreach($collectes as $collecte)<tr><td class="px-6 py-4 text-sm">{{ $collecte->code_collecte }}</td><td class="px-6 py-4 text-sm">{{ $collecte->date_collecte->format('d/m/Y') }}</td><td class="px-6 py-4">{{ $collecte->producteur->nom_complet }}</td><td class="px-6 py-4">{{ $collecte->produit }}</td><td class="px-6 py-4 text-right">{{ number_format($collecte->quantite_nette) }} kg</td><td class="px-6 py-4 text-right">{{ number_format($collecte->montant_total, 0, ',', ' ') }} CFA</td><td class="px-6 py-4 text-center"><a href="{{ route('controleur.collectes.show', $collecte) }}" class="text-blue-600"><i class="fas fa-eye"></i></a></td></tr>@endforeach
            </tbody>
        </table>
    </div>
    <div class="p-6">{{ $collectes->links() }}</div>
</div>
@endsection