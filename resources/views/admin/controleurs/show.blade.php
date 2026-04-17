@extends('layouts.admin')

@section('title', 'Détails contrôleur')
@section('header', 'Fiche contrôleur')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="bg-gradient-to-r from-primary to-secondary px-6 py-4">
            <div class="flex items-center justify-between">
                <h3 class="text-white text-xl font-semibold">Informations du contrôleur</h3>
                <span class="px-3 py-1 bg-white/20 rounded-full text-white text-sm">
                    {{ $controleur->code_controleur }}
                </span>
            </div>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="text-gray-500 text-sm">Nom complet</label>
                    <p class="font-semibold text-lg">{{ $controleur->nom_complet }}</p>
                </div>
                
                <div>
                    <label class="text-gray-500 text-sm">Email</label>
                    <p>{{ $controleur->email }}</p>
                </div>
                
                <div>
                    <label class="text-gray-500 text-sm">Téléphone</label>
                    <p>{{ $controleur->telephone }}</p>
                </div>
                
                <div>
                    <label class="text-gray-500 text-sm">Région de contrôle</label>
                    <p>{{ $controleur->region_controle }}</p>
                </div>
                
                <div>
                    <label class="text-gray-500 text-sm">Date d'embauche</label>
                    <p>{{ $controleur->date_embauche->format('d/m/Y') }}</p>
                </div>
                
                <div>
                    <label class="text-gray-500 text-sm">Nombre de visites</label>
                    <p class="text-2xl font-bold text-primary">{{ number_format($controleur->nombre_visites) }}</p>
                </div>
                
                <div>
                    <label class="text-gray-500 text-sm">Statut</label>
                    <p>
                        <span class="px-2 py-1 text-xs rounded-full {{ $controleur->statut == 'actif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $controleur->statut }}
                        </span>
                    </p>
                </div>
            </div>
            
            <div class="mt-6 pt-6 border-t flex justify-between">
                <a href="{{ route('admin.controleurs.edit', $controleur) }}" class="text-primary hover:underline">
                    <i class="fas fa-edit mr-1"></i>Modifier
                </a>
                <button onclick="openResetModal()" class="text-orange-600 hover:underline">
                    <i class="fas fa-key mr-1"></i>Réinitialiser mot de passe
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="resetModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl p-6 max-w-md w-full">
        <h3 class="text-xl font-bold mb-4">Réinitialiser le mot de passe</h3>
        <p class="text-gray-600 mb-6">Êtes-vous sûr de vouloir réinitialiser le mot de passe de <strong>{{ $controleur->nom_complet }}</strong> ?</p>
        <div class="flex justify-end space-x-3">
            <button onclick="closeResetModal()" class="px-4 py-2 border rounded-lg">Annuler</button>
            <form method="POST" action="{{ route('admin.controleurs.reset-password', $controleur) }}">
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