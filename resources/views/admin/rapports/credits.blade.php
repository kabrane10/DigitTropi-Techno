@extends('layouts.admin')

@section('title', 'Rapport des crédits')
@section('header', 'Rapport des crédits')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-6">
    <div class="flex justify-between items-center mb-6 flex-wrap gap-4">
        <h2 class="text-xl font-semibold">État des crédits agricoles</h2>
        <div class="flex space-x-3">
            <form method="GET" class="flex items-center space-x-2">
                <select name="statut" class="px-3 py-2 border rounded-lg">
                    <option value="">Tous les statuts</option>
                    <option value="actif" {{ $statut == 'actif' ? 'selected' : '' }}>Actifs</option>
                    <option value="rembourse" {{ $statut == 'rembourse' ? 'selected' : '' }}>Remboursés</option>
                    <option value="impaye" {{ $statut == 'impaye' ? 'selected' : '' }}>Impayés</option>
                </select>
                <button type="submit" class="bg-primary text-white px-4 py-2 rounded-lg">Filtrer</button>
            </form>
            <a href="{{ route('admin.rapports.credits', ['export' => 'pdf', 'statut' => $statut]) }}" class="bg-red-500 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-file-pdf mr-2"></i>PDF
            </a>
        </div>
    </div>
    
    <!-- Cartes stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-primary/10 rounded-xl p-4">
            <p class="text-sm text-gray-600">Total crédits</p>
            <p class="text-2xl font-bold text-primary">{{ number_format($stats['total_credits'], 0, ',', ' ') }} CFA</p>
        </div>
        <div class="bg-yellow-100 rounded-xl p-4">
            <p class="text-sm text-gray-600">Crédits actifs</p>
            <p class="text-2xl font-bold text-yellow-600">{{ number_format($stats['credits_actifs'], 0, ',', ' ') }} CFA</p>
        </div>
        <div class="bg-green-100 rounded-xl p-4">
            <p class="text-sm text-gray-600">Taux remboursement</p>
            <p class="text-2xl font-bold text-green-600">{{ number_format($stats['taux_remboursement'], 1) }}%</p>
        </div>
        <div class="bg-blue-100 rounded-xl p-4">
            <p class="text-sm text-gray-600">Nombre crédits</p>
            <p class="text-2xl font-bold text-blue-600">{{ number_format($stats['nb_credits']) }}</p>
        </div>
    </div>
    
    <!-- Tableau des crédits -->
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left">Code</th>
                    <th class="px-4 py-2 text-left">Producteur</th>
                    <th class="px-4 py-2 text-right">Montant</th>
                    <th class="px-4 py-2 text-right">Reste</th>
                    <th class="px-4 py-2 text-left">Échéance</th>
                    <th class="px-4 py-2 text-left">Statut</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($credits as $credit)
                <tr>
                    <td class="px-4 py-2">{{ $credit->code_credit }}</td>
                    <td class="px-4 py-2">{{ $credit->producteur->nom_complet }}</td>
                    <td class="px-4 py-2 text-right">{{ number_format($credit->montant_total, 0, ',', ' ') }} CFA</td>
                    <td class="px-4 py-2 text-right {{ $credit->montant_restant > 0 ? 'text-red-600' : 'text-green-600' }}">
                        {{ number_format($credit->montant_restant, 0, ',', ' ') }} CFA
                    </td>
                    <td class="px-4 py-2">{{ $credit->date_echeance->format('d/m/Y') }}</td>
                    <td class="px-4 py-2">
                        <span class="px-2 py-1 text-xs rounded-full 
                            @if($credit->statut == 'actif') bg-yellow-100 text-yellow-800
                            @elseif($credit->statut == 'rembourse') bg-green-100 text-green-800
                            @else bg-red-100 text-red-800
                            @endif">
                            {{ $credit->statut }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="mt-6">
        {{ $credits->links() }}
    </div>
</div>
@endsection