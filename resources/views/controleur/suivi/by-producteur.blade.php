@extends('layouts.controleur')

@section('title', 'Suivis par producteur')
@section('header', 'Historique des suivis - ' . $producteur->nom_complet)

@section('content')
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="bg-gradient-to-r from-primary to-secondary px-6 py-4">
        <div class="flex justify-between items-center">
            <div>
                <h3 class="text-white text-xl font-semibold">Suivis de {{ $producteur->nom_complet }}</h3>
                <p class="text-white/80 text-sm">Code: {{ $producteur->code_producteur }}</p>
            </div>
            <span class="px-3 py-1 bg-white/20 rounded-full text-white text-sm">
                {{ $suivis->count() }} suivi(s)
            </span>
        </div>
    </div>
    
    <div class="p-6">
        @if($suivis->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left">Code</th>
                        <th class="px-4 py-3 text-left">Date</th>
                        <th class="px-4 py-3 text-left">Animateur</th>
                        <th class="px-4 py-3 text-right">Superficie</th>
                        <th class="px-4 py-3 text-left">Stade</th>
                        <th class="px-4 py-3 text-left">Santé</th>
                        <th class="px-4 py-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($suivis as $suivi)
                    <tr>
                        <td class="px-4 py-3">{{ $suivi->code_suivi }}</td>
                        <td class="px-4 py-3">{{ $suivi->date_suivi->format('d/m/Y') }}</td>
                        <td class="px-4 py-3">{{ $suivi->animateur->nom_complet }}</td>
                        <td class="px-4 py-3 text-right">{{ number_format($suivi->superficie_actuelle, 2) }} ha</td>
                        <td class="px-4 py-3">{{ $suivi->stade_croissance }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 text-xs rounded-full 
                                @if($suivi->sante_cultures == 'excellente') bg-green-100 text-green-800
                                @elseif($suivi->sante_cultures == 'bonne') bg-blue-100 text-blue-800
                                @elseif($suivi->sante_cultures == 'moyenne') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ $suivi->sante_cultures }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <a href="{{ route('controleur.suivi.show', $suivi) }}" class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Graphique d'évolution -->
        <div class="mt-8 p-4 bg-gray-50 rounded-lg">
            <h4 class="font-semibold mb-3">📈 Évolution de la superficie</h4>
            <canvas id="evolutionChart" height="100"></canvas>
        </div>
        
        @else
        <div class="text-center py-8 text-gray-500">
            <i class="fas fa-clipboard-list text-4xl mb-2 opacity-50"></i>
            <p>Aucun suivi pour ce producteur</p>
        </div>
        @endif
        
        <div class="mt-6 pt-4 border-t">
            <a href="{{ route('controleur.suivi.index') }}" class="text-gray-600 hover:text-gray-800">
                <i class="fas fa-arrow-left mr-1"></i> Retour à la liste
            </a>
        </div>
    </div>
</div>

@if($suivis->count() > 0)
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const suivis = @json($suivis);
    const labels = suivis.reverse().map(s => s.date_suivi);
    const superficies = suivis.map(s => s.superficie_actuelle);
    
    new Chart(document.getElementById('evolutionChart'), {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Superficie (ha)',
                data: superficies,
                borderColor: '#2d6a4f',
                backgroundColor: 'rgba(45, 106, 79, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: { responsive: true, maintainAspectRatio: true }
    });
</script>
@endif
@endsection