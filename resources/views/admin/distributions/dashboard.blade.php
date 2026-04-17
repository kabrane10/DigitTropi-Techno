@extends('layouts.admin')

@section('title', 'Dashboard distributions')
@section('header', 'Tableau de bord des distributions')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-primary">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Total distributions</p>
                <p class="text-3xl font-bold">{{ number_format($stats['total_distributions']) }}</p>
            </div>
            <i class="fas fa-truck text-primary text-3xl opacity-50"></i>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-secondary">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Quantité totale</p>
                <p class="text-3xl font-bold">{{ number_format($stats['total_quantite']) }} kg</p>
            </div>
            <i class="fas fa-weight-hanging text-secondary text-3xl opacity-50"></i>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-accent">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Superficie emblavée</p>
                <p class="text-3xl font-bold">{{ number_format($stats['total_superficie'], 2) }} ha</p>
            </div>
            <i class="fas fa-map-marked-alt text-accent text-3xl opacity-50"></i>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Distributions par saison -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-semibold mb-4">Distributions par saison</h3>
        <div class="space-y-3">
            @foreach($stats['distributions_par_saison'] as $item)
            <div>
                <div class="flex justify-between mb-1">
                    <span class="text-sm font-medium">
                        @if($item->saison == 'principale') 🌾 Principale
                        @elseif($item->saison == 'contre-saison') 🌱 Contre-saison
                        @else 💧 Hivernage
                        @endif
                    </span>
                    <span class="text-sm text-gray-500">{{ $item->total }} distributions</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    @php
                        $max = $stats['distributions_par_saison']->max('total');
                        $pourcentage = $max > 0 ? ($item->total / $max) * 100 : 0;
                    @endphp
                    <div class="bg-primary h-2 rounded-full" style="width: {{ $pourcentage }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    
    <!-- Top semences distribuées -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-semibold mb-4">Top 5 semences les plus distribuées</h3>
        <div class="space-y-3">
            @foreach($stats['top_semences'] as $item)
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <div>
                    <p class="font-medium">{{ $item->semence->nom }} ({{ $item->semence->variete }})</p>
                    <p class="text-xs text-gray-500">Code: {{ $item->semence->code_semence }}</p>
                </div>
                <div class="text-right">
                    <p class="font-semibold text-primary">{{ number_format($item->total) }} kg</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Dernières distributions -->
<div class="bg-white rounded-xl shadow-sm p-6 mt-6">
    <h3 class="text-lg font-semibold mb-4">Dernières distributions</h3>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left text-xs">Date</th>
                    <th class="px-4 py-2 text-left text-xs">Producteur</th>
                    <th class="px-4 py-2 text-left text-xs">Semence</th>
                    <th class="px-4 py-2 text-right text-xs">Quantité</th>
                    <th class="px-4 py-2 text-right text-xs">Superficie</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($stats['distributions_recentes'] as $dist)
                <tr>
                    <td class="px-4 py-2 text-sm">{{ $dist->date_distribution->format('d/m/Y') }}</td>
                    <td class="px-4 py-2">{{ $dist->producteur->nom_complet }}</td>
                    <td class="px-4 py-2">{{ $dist->semence->nom }}</td>
                    <td class="px-4 py-2 text-right">{{ number_format($dist->quantite) }} {{ $dist->semence->unite }}</td>
                    <td class="px-4 py-2 text-right">{{ number_format($dist->superficie_emblevee, 2) }} ha</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection