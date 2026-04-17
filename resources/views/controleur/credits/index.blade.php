@extends('layouts.controleur')

@section('title', 'Crédits')
@section('header', 'Liste des crédits')

@section('content')
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b">
        <h2 class="text-xl font-semibold">Tous les crédits</h2>
    </div>
    
    <div class="p-6 border-b bg-gray-50">
        <form method="GET" class="flex flex-wrap gap-4">
            <select name="statut" class="px-4 py-2 border rounded-lg">
                <option value="">Tous statuts</option>
                <option value="actif" {{ request('statut') == 'actif' ? 'selected' : '' }}>Actif</option>
                <option value="rembourse" {{ request('statut') == 'rembourse' ? 'selected' : '' }}>Remboursé</option>
            </select>
            <button type="submit" class="bg-primary text-white px-4 py-2 rounded-lg">Filtrer</button>
        </form>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left">Code</th>
                    <th class="px-6 py-3 text-left">Producteur</th>
                    <th class="px-6 py-3 text-right">Montant</th>
                    <th class="px-6 py-3 text-right">Reste</th>
                    <th class="px-6 py-3 text-left">Échéance</th>
                    <th class="px-6 py-3 text-left">Statut</th>
                    <th class="px-6 py-3 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($credits as $credit)
                <tr>
                    <td class="px-6 py-4">{{ $credit->code_credit }}</td>
                    <td class="px-6 py-4">{{ $credit->producteur->nom_complet }}</td>
                    <td class="px-6 py-4 text-right">{{ number_format($credit->montant_total, 0, ',', ' ') }} CFA</td>
                    <td class="px-6 py-4 text-right {{ $credit->montant_restant > 0 ? 'text-red-600' : 'text-green-600' }}">{{ number_format($credit->montant_restant, 0, ',', ' ') }} CFA</td>
                    <td class="px-6 py-4">{{ $credit->date_echeance->format('d/m/Y') }}</td>
                    <td class="px-6 py-4"><span class="px-2 py-1 text-xs rounded-full {{ $credit->statut == 'actif' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">{{ $credit->statut }}</span></td>
                    <td class="px-6 py-4 text-center"><a href="{{ route('controleur.credits.show', $credit) }}" class="text-blue-600"><i class="fas fa-eye"></i></a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="p-6">{{ $credits->links() }}</div>
</div>
@endsection