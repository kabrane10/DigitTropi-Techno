@extends('layouts.controleur')

@section('title', 'Producteurs')
@section('header', 'Liste des producteurs')

@section('content')
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b">
        <h2 class="text-xl font-semibold">Tous les producteurs</h2>
    </div>
    
    <div class="p-6 border-b bg-gray-50">
        <form method="GET" class="flex flex-wrap gap-4">
            <input type="text" name="search" placeholder="Rechercher..." value="{{ request('search') }}" class="px-4 py-2 border rounded-lg">
            <select name="region" class="px-4 py-2 border rounded-lg">
                <option value="">Toutes régions</option>
                <option value="Centrale" {{ request('region') == 'Centrale' ? 'selected' : '' }}>Centrale</option>
                <option value="Kara" {{ request('region') == 'Kara' ? 'selected' : '' }}>Kara</option>
                <option value="Savanes" {{ request('region') == 'Savanes' ? 'selected' : '' }}>Savanes</option>
            </select>
            <button type="submit" class="bg-primary text-white px-4 py-2 rounded-lg">Filtrer</button>
        </form>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left">Code</th>
                    <th class="px-6 py-3 text-left">Nom</th>
                    <th class="px-6 py-3 text-left">Contact</th>
                    <th class="px-6 py-3 text-left">Région</th>
                    <th class="px-6 py-3 text-left">Culture</th>
                    <th class="px-6 py-3 text-right">Superficie</th>
                    <th class="px-6 py-3 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($producteurs as $producteur)
                <tr>
                    <td class="px-6 py-4 text-sm">{{ $producteur->code_producteur }}</td>
                    <td class="px-6 py-4 font-medium">{{ $producteur->nom_complet }}</td>
                    <td class="px-6 py-4 text-sm">{{ $producteur->contact }}</td>
                    <td class="px-6 py-4 text-sm">{{ $producteur->region }}</td>
                    <td class="px-6 py-4 text-sm">{{ $producteur->culture_pratiquee }}</td>
                    <td class="px-6 py-4 text-right text-sm">{{ number_format($producteur->superficie_totale, 2) }} ha</td>
                    <td class="px-6 py-4 text-center">
                        <a href="{{ route('controleur.producteurs.show', $producteur) }}" class="text-blue-600"><i class="fas fa-eye"></i></a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="p-6">
        {{ $producteurs->links() }}
    </div>
</div>
@endsection