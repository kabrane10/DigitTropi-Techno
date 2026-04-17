@extends('layouts.animateur')

@section('title', 'Rapports')
@section('header', 'Tableau de bord des rapports')

@section('content')
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl p-4 border-l-4 border-primary"><div class="flex justify-between"><div><p class="text-gray-500 text-sm">Producteurs</p><p class="text-2xl font-bold">{{ number_format($stats['total_producteurs']) }}</p></div><i class="fas fa-users text-primary text-2xl"></i></div></div>
    <div class="bg-white rounded-xl p-4 border-l-4 border-green-500"><div class="flex justify-between"><div><p class="text-gray-500 text-sm">Collectes totales</p><p class="text-2xl font-bold">{{ number_format($stats['total_collectes']) }} kg</p><p class="text-xs">{{ number_format($stats['valeur_collectes'],0,',',' ') }} CFA</p></div><i class="fas fa-truck-loading text-green-500 text-2xl"></i></div></div>
    <div class="bg-white rounded-xl p-4 border-l-4 border-blue-500"><div class="flex justify-between"><div><p class="text-gray-500 text-sm">Agents</p><p class="text-2xl font-bold">{{ number_format($stats['total_agents']) }}</p></div><i class="fas fa-users text-blue-500 text-2xl"></i></div></div>
    <div class="bg-white rounded-xl p-4 border-l-4 border-purple-500"><div class="flex justify-between"><div><p class="text-gray-500 text-sm">Superficie totale</p><p class="text-2xl font-bold">{{ number_format($stats['total_superficie'],2) }} ha</p></div><i class="fas fa-map-marked-alt text-purple-500 text-2xl"></i></div></div>
</div>
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-xl p-6"><h3 class="font-semibold mb-4">Évolution des collectes</h3><canvas id="collectesChart" height="200"></canvas></div>
    <div class="bg-white rounded-xl p-6"><h3 class="font-semibold mb-4">Top producteurs</h3><div class="space-y-3">@foreach($topProducteurs as $p)<div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg"><div><p class="font-medium">{{ $p->nom_complet }}</p><p class="text-xs text-gray-500">{{ $p->code_producteur }}</p></div><div class="text-right"><p class="font-semibold text-primary">{{ number_format($p->collectes_sum_quantite_nette ?? 0) }} kg</p></div></div>@endforeach</div></div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>new Chart(document.getElementById('collectesChart'),{type:'line',data:{labels:{!! json_encode($collectesParMois->pluck('mois')->reverse()) !!},datasets:[{label:'Collectes (kg)',data:{!! json_encode($collectesParMois->pluck('total')->reverse()) !!},borderColor:'#2d6a4f',fill:true}]}});</script>
@endsection