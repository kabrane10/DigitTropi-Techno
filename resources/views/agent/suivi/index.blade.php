@extends('layouts.agent')

@section('title', 'Suivi terrain')
@section('header', 'Suivi des parcelles')

@section('content')
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b flex justify-between items-center">
        <h2 class="text-xl font-semibold">Liste des suivis</h2>
        <a href="{{ route('agent.suivi.create') }}" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-secondary">
            <i class="fas fa-plus mr-2"></i>Nouveau suivi
        </a>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs">Code</th>
                    <th class="px-6 py-3 text-left text-xs">Date</th>
                    <th class="px-6 py-3 text-left text-xs">Producteur</th>
                    <th class="px-6 py-3 text-left text-xs">Superficie</th>
                    <th class="px-6 py-3 text-left text-xs">Stade</th>
                    <th class="px-6 py-3 text-left text-xs">Santé</th>
                    <th class="px-6 py-3 text-center text-xs">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($suivis as $suivi)
                <tr>
                    <td class="px-6 py-4 text-sm">{{ $suivi->code_suivi }}</td>
                    <td class="px-6 py-4 text-sm">{{ $suivi->date_suivi->format('d/m/Y') }}</td>
                    <td class="px-6 py-4">{{ $suivi->producteur->nom_complet }}</td>
                    <td class="px-6 py-4 text-sm">{{ number_format($suivi->superficie_actuelle, 2) }} ha</td>
                    <td class="px-6 py-4 text-sm">{{ $suivi->stade_croissance }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full 
                            @if($suivi->sante_cultures == 'excellente') bg-green-100 text-green-800
                            @elseif($suivi->sante_cultures == 'bonne') bg-blue-100 text-blue-800
                            @elseif($suivi->sante_cultures == 'moyenne') bg-yellow-100 text-yellow-800
                            @else bg-red-100 text-red-800
                            @endif">
                            {{ $suivi->sante_cultures }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <a href="{{ route('agent.suivi.show', $suivi) }}" class="text-blue-600"><i class="fas fa-eye"></i></a>
                        <a href="{{ route('agent.suivi.edit', $suivi) }}" class="text-green-600"><i class="fas fa-edit"></i></a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="p-6">
        {{ $suivis->links() }}
    </div>
</div>
@endsection