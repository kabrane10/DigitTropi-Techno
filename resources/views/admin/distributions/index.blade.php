@extends('layouts.admin')

@section('title', 'Distributions de semences')
@section('header', 'Gestion des distributions')

@section('content')
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b flex justify-between items-center flex-wrap gap-4">
        <h2 class="text-xl font-semibold">Liste des distributions</h2>
        <div class="flex space-x-3">
            <a href="{{ route('admin.distributions.dashboard') }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                <i class="fas fa-chart-line mr-2"></i>Dashboard
            </a>
            <a href="{{ route('admin.distributions.create') }}" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-secondary">
                <i class="fas fa-plus mr-2"></i>Nouvelle distribution
            </a>
        </div>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Code</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Producteur</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Semence</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Quantité</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Superficie</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Saison</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($distributions as $distribution)
                <tr>
                    <td class="px-6 py-4 text-sm">{{ $distribution->code_distribution }}</td>
                    <td class="px-6 py-4 text-sm">{{ $distribution->date_distribution->format('d/m/Y') }}</td>
                    <td class="px-6 py-4">{{ $distribution->producteur->nom_complet }}</td>
                    <td class="px-6 py-4">{{ $distribution->semence->nom }} ({{ $distribution->semence->variete }})</td>
                    <td class="px-6 py-4 text-sm">{{ number_format($distribution->quantite) }} {{ $distribution->semence->unite }}</td>
                    <td class="px-6 py-4 text-sm">{{ number_format($distribution->superficie_emblevee, 2) }} ha</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full 
                            @if($distribution->saison == 'principale') bg-green-100 text-green-800
                            @elseif($distribution->saison == 'contre-saison') bg-yellow-100 text-yellow-800
                            @else bg-blue-100 text-blue-800
                            @endif">
                            {{ $distribution->saison }}
                        </span>
                    </td>
                    <td class="px-6 py-4 space-x-2">
                        <a href="{{ route('admin.distributions.show', $distribution) }}" class="text-blue-600"><i class="fas fa-eye"></i></a>
                        <a href="{{ route('admin.distributions.edit', $distribution) }}" class="text-green-600"><i class="fas fa-edit"></i></a>
                        <form action="{{ route('admin.distributions.destroy', $distribution) }}" method="POST" class="inline delete-confirm">
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
        {{ $distributions->links() }}
    </div>
</div>
@endsection