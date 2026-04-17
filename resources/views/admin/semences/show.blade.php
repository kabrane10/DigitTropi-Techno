@extends('layouts.admin')

@section('title', 'Détails semence')
@section('header', 'Fiche semence')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Carte informations -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="text-center mb-4">
                <div class="w-24 h-24 bg-primary/20 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-seedling text-primary text-4xl"></i>
                </div>
                <h3 class="text-xl font-bold">{{ $semence->nom }}</h3>
                <p class="text-gray-500">{{ $semence->variete }}</p>
                <p class="text-xs text-gray-400 mt-1">{{ $semence->code_semence }}</p>
            </div>
            
            <div class="space-y-3">
                <div class="flex items-center">
                    <i class="fas fa-tag w-8 text-gray-400"></i>
                    <span>Type: <span class="font-semibold">{{ ucfirst($semence->type) }}</span></span>
                </div>
                <div class="flex items-center">
                    <i class="fas fa-money-bill-wave w-8 text-gray-400"></i>
                    <span>Prix: {{ number_format($semence->prix_unitaire, 0, ',', ' ') }} CFA/{{ $semence->unite }}</span>
                </div>
                <div class="flex items-center">
                    <i class="fas fa-boxes w-8 text-gray-400"></i>
                    <span>Stock: <span class="font-bold {{ $semence->stock_disponible <= 100 ? 'text-red-600' : 'text-green-600' }}">{{ number_format($semence->stock_disponible) }} {{ $semence->unite }}</span></span>
                </div>
                <div class="flex items-center">
                    <i class="fas fa-ruler-combined w-8 text-gray-400"></i>
                    <span>Unité: {{ $semence->unite }}</span>
                </div>
            </div>
            
            <div class="mt-6 pt-4 border-t">
                <button onclick="openAjoutStockModal({{ $semence->id }}, '{{ $semence->nom }}')" class="w-full bg-orange-500 text-white px-4 py-2 rounded-lg hover:bg-orange-600">
                    <i class="fas fa-plus-circle mr-2"></i>Ajouter du stock
                </button>
            </div>
        </div>
    </div>
    
    <!-- Statistiques et distributions -->
    <div class="lg:col-span-2 space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-blue-500 rounded-xl p-4 text-white">
                <p class="text-sm opacity-90">Total distribué</p>
                <p class="text-2xl font-bold">{{ number_format($stats['total_distribue']) }} {{ $semence->unite }}</p>
            </div>
            <div class="bg-green-500 rounded-xl p-4 text-white">
                <p class="text-sm opacity-90">Nombre de distributions</p>
                <p class="text-2xl font-bold">{{ number_format($stats['nb_distributions']) }}</p>
            </div>
            <div class="bg-purple-500 rounded-xl p-4 text-white">
                <p class="text-sm opacity-90">Producteurs servis</p>
                <p class="text-2xl font-bold">{{ number_format($stats['nb_producteurs']) }}</p>
            </div>
        </div>
        
        <!-- Dernières distributions -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold mb-4">Dernières distributions</h3>
            @if($semence->distributions->count() > 0)
            <div class="space-y-3">
                @foreach($semence->distributions->take(10) as $dist)
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <div>
                        <p class="font-medium">{{ $dist->producteur->nom_complet }}</p>
                        <p class="text-xs text-gray-500">{{ $dist->date_distribution->format('d/m/Y') }} - {{ $dist->saison }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-primary">{{ number_format($dist->quantite) }} {{ $semence->unite }}</p>
                        <p class="text-xs text-gray-500">{{ number_format($dist->superficie_emblevee, 2) }} ha</p>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <p class="text-gray-500 text-center py-6">Aucune distribution enregistrée</p>
            @endif
        </div>
    </div>
</div>

<!-- Modal Ajout Stock -->
<div id="ajoutStockModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl p-6 max-w-md w-full">
        <h3 class="text-xl font-bold mb-4">Ajouter du stock</h3>
        <p class="text-gray-600 mb-4">Semence: <strong id="semenceNom"></strong></p>
        <form id="ajoutStockForm" method="POST" action="">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-semibold mb-2">Quantité à ajouter ({{ $semence->unite }})</label>
                <input type="number" step="0.01" name="quantite" required class="w-full px-4 py-2 border rounded-lg">
            </div>
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeAjoutStockModal()" class="px-4 py-2 border rounded-lg">Annuler</button>
                <button type="submit" class="bg-primary text-white px-4 py-2 rounded-lg">Ajouter</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openAjoutStockModal(id, nom) {
        const modal = document.getElementById('ajoutStockModal');
        const form = document.getElementById('ajoutStockForm');
        const semenceNom = document.getElementById('semenceNom');
        semenceNom.textContent = nom;
        form.action = "/admin/semences/" + id + "/ajouter-stock";
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
    
    function closeAjoutStockModal() {
        const modal = document.getElementById('ajoutStockModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
</script>
@endsection