@extends('layouts.admin')

@section('title', 'Dashboard intrants')
@section('header', ' Tableau de bord des intrants')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-primary">
        <div class="flex items-center justify-between">
            <div><p class="text-gray-500 text-sm">Total intrants</p><p class="text-3xl font-bold">{{ number_format($stats['total_intrants']) }}</p></div>
            <i class="fas fa-boxes text-primary text-3xl opacity-50"></i>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-blue-500">
        <div class="flex items-center justify-between">
            <div><p class="text-gray-500 text-sm">Stock total (kg)</p><p class="text-3xl font-bold">{{ number_format($stats['total_stock']) }}</p></div>
            <i class="fas fa-weight-hanging text-blue-500 text-3xl opacity-50"></i>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-red-500">
        <div class="flex items-center justify-between">
            <div><p class="text-gray-500 text-sm">Alertes stock</p><p class="text-3xl font-bold text-red-600">{{ number_format($stats['alertes']) }}</p></div>
            <i class="fas fa-exclamation-triangle text-red-500 text-3xl opacity-50"></i>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-green-500">
        <div class="flex items-center justify-between">
            <div><p class="text-gray-500 text-sm">Valeur du stock</p><p class="text-3xl font-bold">{{ number_format($stats['valeur_stock'], 0, ',', ' ') }} CFA</p></div>
            <i class="fas fa-money-bill-wave text-green-500 text-3xl opacity-50"></i>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-semibold mb-4">Stock par zone</h3>
        <div class="space-y-4">
            @foreach($stocksParZone as $zone)
            <div>
                <div class="flex justify-between mb-1"><span class="font-medium">{{ $zone->zone }}</span><span>{{ number_format($zone->total) }} kg</span></div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    @php $max = $stocksParZone->max('total'); $pourcentage = $max > 0 ? ($zone->total / $max) * 100 : 0; @endphp
                    <div class="bg-primary h-2 rounded-full" style="width: {{ $pourcentage }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-semibold mb-4">Actions rapides</h3>
        <div class="space-y-3">
            <a href="{{ route('admin.intrants.create') }}" class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100">
                <span><i class="fas fa-plus-circle text-primary mr-2"></i>Ajouter un intrant</span>
                <i class="fas fa-arrow-right text-gray-400"></i>
            </a>
            <a href="{{ route('admin.intrants.alertes') }}" class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100">
                <span><i class="fas fa-exclamation-triangle text-orange-500 mr-2"></i>Voir les alertes</span>
                <i class="fas fa-arrow-right text-gray-400"></i>
            </a>
            <a href="{{ route('admin.intrants.index') }}" class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100">
                <span><i class="fas fa-list text-primary mr-2"></i>Gérer les intrants</span>
                <i class="fas fa-arrow-right text-gray-400"></i>
            </a>
        </div>
    </div>
</div>
@endsection