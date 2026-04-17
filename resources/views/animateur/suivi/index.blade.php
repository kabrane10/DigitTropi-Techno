@extends('layouts.animateur')

@section('title', 'Suivi terrain')
@section('header', 'Liste des suivis')

@section('content')
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-4 sm:p-6 border-b"><h2 class="text-xl font-semibold">Suivis parcellaires</h2></div>
    <div class="p-4 border-b bg-gray-50"><form method="GET" class="flex flex-wrap gap-4"><select name="producteur_id" class="px-4 py-2 border rounded-lg"><option value="">Tous producteurs</option>@foreach($producteurs as $p)<option value="{{ $p->id }}">{{ $p->nom_complet }}</option>@endforeach</select><button type="submit" class="bg-primary text-white px-4 py-2 rounded-lg">Filtrer</button></form></div>
    <div class="overflow-x-auto"><table class="w-full"><thead class="bg-gray-50"><tr><th class="px-4 py-3 text-left">Code</th><th class="px-4 py-3 text-left">Date</th><th class="px-4 py-3 text-left">Producteur</th><th class="px-4 py-3 text-left">Animateur</th><th class="px-4 py-3 text-right">Superficie</th><th class="px-4 py-3 text-left">Santé</th><th class="px-4 py-3 text-center">Actions</th></tr></thead>
        <tbody class="divide-y">@foreach($suivis as $s)<tr><td class="px-4 py-3 text-sm">{{ $s->code_suivi }}</td><td class="px-4 py-3 text-sm">{{ $s->date_suivi->format('d/m/Y') }}</td><td class="px-4 py-3">{{ $s->producteur->nom_complet }}</td><td class="px-4 py-3">{{ $s->animateur->nom_complet }}</td><td class="px-4 py-3 text-right">{{ number_format($s->superficie_actuelle,2) }} ha</td><td class="px-4 py-3"><span class="px-2 py-1 text-xs rounded-full {{ $s->sante_cultures=='excellente'?'bg-green-100 text-green-800':($s->sante_cultures=='bonne'?'bg-blue-100 text-blue-800':'bg-red-100 text-red-800') }}">{{ $s->sante_cultures }}</span></td><td class="px-4 py-3 text-center"><a href="{{ route('animateur.suivi.show', $s) }}" class="text-blue-600"><i class="fas fa-eye"></i></a></td></tr>@endforeach</tbody>
    </table></div>
    <div class="p-4">{{ $suivis->links() }}</div>
</div>
@endsection