@extends('layouts.animateur')

@section('title', 'Producteurs')
@section('header', 'Liste des producteurs')

@section('content')
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-4 sm:p-6 border-b"><h2 class="text-xl font-semibold">Producteurs de ma zone</h2></div>
    <div class="p-4 border-b bg-gray-50"><form method="GET" class="flex flex-wrap gap-4"><input type="text" name="search" placeholder="Rechercher..." value="{{ request('search') }}" class="px-4 py-2 border rounded-lg"><select name="region" class="px-4 py-2 border rounded-lg"><option value="">Toutes régions</option><option value="Centrale">Centrale</option><option value="Kara">Kara</option><option value="Savanes">Savanes</option></select><button type="submit" class="bg-primary text-white px-4 py-2 rounded-lg">Filtrer</button></form></div>
    <div class="overflow-x-auto"><table class="w-full"><thead class="bg-gray-50"><tr><th class="px-4 py-3 text-left">Code</th><th class="px-4 py-3 text-left">Nom</th><th class="px-4 py-3 text-left">Contact</th><th class="px-4 py-3 text-left">Région</th><th class="px-4 py-3 text-left">Culture</th><th class="px-4 py-3 text-right">Superficie</th><th class="px-4 py-3 text-center">Actions</th></tr></thead>
        <tbody class="divide-y">@foreach($producteurs as $p)<tr><td class="px-4 py-3 text-sm">{{ $p->code_producteur }}</td><td class="px-4 py-3 font-medium">{{ $p->nom_complet }}</td><td class="px-4 py-3 text-sm">{{ $p->contact }}</td><td class="px-4 py-3 text-sm">{{ $p->region }}</td><td class="px-4 py-3 text-sm">{{ $p->culture_pratiquee }}</td><td class="px-4 py-3 text-right text-sm">{{ number_format($p->superficie_totale,2) }} ha</td><td class="px-4 py-3 text-center"><a href="{{ route('animateur.producteurs.show', $p) }}" class="text-blue-600"><i class="fas fa-eye"></i></a></td></tr>@endforeach</tbody>
    </table></div>
    <div class="p-4">{{ $producteurs->links() }}</div>
</div>
@endsection