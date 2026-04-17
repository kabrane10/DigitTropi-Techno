@extends('layouts.controleur')

@section('title', 'Suivi terrain')
@section('header', 'Liste des suivis parcellaires')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-primary"><div><p class="text-gray-500">Total suivis</p><p class="text-2xl font-bold">{{ number_format($stats['total_suivis']) }}</p></div></div>
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-green-500"><div><p class="text-gray-500">Suivis ce mois</p><p class="text-2xl font-bold">{{ number_format($stats['suivis_mois']) }}</p></div></div>
</div>

<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b"><h2 class="text-xl font-semibold">Liste des suivis</h2></div>
    <div class="p-6 border-b bg-gray-50">
        <form method="GET" class="flex flex-wrap gap-4">
            <select name="producteur_id" class="px-4 py-2 border rounded-lg"><option value="">Tous producteurs</option>@foreach($producteurs as $p)<option value="{{ $p->id }}">{{ $p->nom_complet }}</option>@endforeach</select>
            <select name="sante" class="px-4 py-2 border rounded-lg"><option value="">Tous états</option><option value="excellente">Excellente</option><option value="bonne">Bonne</option><option value="moyenne">Moyenne</option><option value="mauvaise">Mauvaise</option><option value="critique">Critique</option></select>
            <button type="submit" class="bg-primary text-white px-4 py-2 rounded-lg">Filtrer</button>
        </form>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50"><tr><th>Code</th><th>Date</th><th>Producteur</th><th>Animateur</th><th>Superficie</th><th>Santé</th><th>Actions</th></tr></thead>
            <tbody class="divide-y">
                @foreach($suivis as $suivi)
                <tr><td class="px-6 py-4">{{ $suivi->code_suivi }}</td><td class="px-6 py-4">{{ $suivi->date_suivi->format('d/m/Y') }}</td><td class="px-6 py-4">{{ $suivi->producteur->nom_complet }}</td><td class="px-6 py-4">{{ $suivi->animateur->nom_complet }}</td><td class="px-6 py-4">{{ number_format($suivi->superficie_actuelle, 2) }} ha</td>
                    <td class="px-6 py-4"><span class="px-2 py-1 text-xs rounded-full {{ $suivi->sante_cultures == 'excellente' ? 'bg-green-100 text-green-800' : ($suivi->sante_cultures == 'bonne' ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800') }}">{{ $suivi->sante_cultures }}</span></td>
                    <td class="px-6 py-4"><a href="{{ route('controleur.suivi.show', $suivi) }}" class="text-blue-600"><i class="fas fa-eye"></i></a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="p-6">{{ $suivis->links() }}</div>
</div>
@endsection