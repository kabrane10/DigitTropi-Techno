@extends('layouts.admin')

@section('title', 'Détails animateur')
@section('header', 'Fiche animateur')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Carte informations -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="text-center mb-4">
                <div class="w-24 h-24 bg-primary/20 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-user-tie text-primary text-4xl"></i>
                </div>
                <h3 class="text-xl font-bold">{{ $animateur->nom_complet }}</h3>
                <p class="text-gray-500">{{ $animateur->code_animateur }}</p>
            </div>
            
            <div class="space-y-3">
                <div class="flex items-center">
                    <i class="fas fa-envelope w-8 text-gray-400"></i>
                    <span>{{ $animateur->email }}</span>
                </div>
                <div class="flex items-center">
                    <i class="fas fa-phone w-8 text-gray-400"></i>
                    <span>{{ $animateur->telephone }}</span>
                </div>
                <div class="flex items-center">
                    <i class="fas fa-map-marker-alt w-8 text-gray-400"></i>
                    <span>{{ $animateur->region }} - {{ $animateur->zone_responsabilite }}</span>
                </div>
                <div class="flex items-center">
                    <i class="fas fa-calendar w-8 text-gray-400"></i>
                    <span>Embauché le {{ $animateur->date_embauche->format('d/m/Y') }}</span>
                </div>
                <div class="flex items-center">
                    <i class="fas fa-tag w-8 text-gray-400"></i>
                    <span class="px-2 py-1 rounded-full text-xs 
                        @if($animateur->statut == 'actif') bg-green-100 text-green-800
                        @elseif($animateur->statut == 'inactif') bg-red-100 text-red-800
                        @else bg-yellow-100 text-yellow-800
                        @endif">
                        {{ $animateur->statut }}
                    </span>
                </div>
            </div>
            
            <div class="mt-6 pt-6 border-t">
                <div class="flex justify-between">
                    <a href="{{ route('admin.animateurs.edit', $animateur) }}" class="text-primary hover:underline">
                        <i class="fas fa-edit mr-1"></i>Modifier
                    </a>
                    <button onclick="openResetModal({{ $animateur->id }}, '{{ $animateur->nom_complet }}')" class="text-orange-600 hover:underline">
                        <i class="fas fa-key mr-1"></i>Réinitialiser mot de passe
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Statistiques et agents -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl p-4 text-white">
                <p class="text-sm opacity-90">Agents supervisés</p>
                <p class="text-3xl font-bold">{{ $stats['agents_count'] }}</p>
            </div>
            <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl p-4 text-white">
                <p class="text-sm opacity-90">Agents actifs</p>
                <p class="text-3xl font-bold">{{ $stats['agents_actifs'] }}</p>
            </div>
            <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl p-4 text-white">
                <p class="text-sm opacity-90">Producteurs suivis</p>
                <p class="text-3xl font-bold">{{ number_format($stats['total_producteurs']) }}</p>
            </div>
        </div>
        
        <!-- Liste des agents -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold mb-4">Agents terrain supervisés</h3>
            
            @if($animateur->agents->count() > 0)
            <div class="space-y-3">
                @foreach($animateur->agents as $agent)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div>
                        <p class="font-medium">{{ $agent->nom_complet }}</p>
                        <p class="text-xs text-gray-500">{{ $agent->code_agent }} - {{ $agent->zone_affectation }}</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <span class="px-2 py-1 text-xs rounded-full {{ $agent->statut == 'actif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $agent->statut }}
                        </span>
                        <a href="{{ route('admin.agents.show', $agent) }}" class="text-primary">
                            <i class="fas fa-eye"></i>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <p class="text-gray-500 text-center py-6">Aucun agent terrain assigné</p>
            @endif
        </div>
    </div>
</div>

<!-- Modal -->
<div id="resetModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl p-6 max-w-md w-full">
        <h3 class="text-xl font-bold mb-4">Réinitialiser le mot de passe</h3>
        <p class="text-gray-600 mb-6">Êtes-vous sûr de vouloir réinitialiser le mot de passe de <strong>{{ $animateur->nom_complet }}</strong> ?</p>
        <div class="flex justify-end space-x-3">
            <button onclick="closeResetModal()" class="px-4 py-2 border rounded-lg">Annuler</button>
            <form id="resetForm" method="POST" action="{{ route('admin.animateurs.reset-password', $animateur) }}">
                @csrf
                <button type="submit" class="bg-orange-500 text-white px-4 py-2 rounded-lg">Réinitialiser</button>
            </form>
        </div>
    </div>
</div>

<script>
    function openResetModal() {
        document.getElementById('resetModal').classList.remove('hidden');
        document.getElementById('resetModal').classList.add('flex');
    }
    function closeResetModal() {
        document.getElementById('resetModal').classList.add('hidden');
        document.getElementById('resetModal').classList.remove('flex');
    }
</script>
@endsection