@extends('layouts.admin')

@section('title', 'Détails agent terrain')
@section('header', 'Fiche agent terrain')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Carte informations -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="text-center mb-4">
                <div class="w-24 h-24 bg-primary/20 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-user-circle text-primary text-5xl"></i>
                </div>
                <h3 class="text-xl font-bold">{{ $agent->nom_complet }}</h3>
                <p class="text-gray-500">{{ $agent->code_agent }}</p>
            </div>
            
            <div class="space-y-3">
                <div class="flex items-center">
                    <i class="fas fa-envelope w-8 text-gray-400"></i>
                    <span>{{ $agent->email }}</span>
                </div>
                <div class="flex items-center">
                    <i class="fas fa-phone w-8 text-gray-400"></i>
                    <span>{{ $agent->telephone }}</span>
                </div>
                <div class="flex items-center">
                    <i class="fas fa-map-marker-alt w-8 text-gray-400"></i>
                    <span>{{ $agent->zone_affectation }}</span>
                </div>
                <div class="flex items-center">
                    <i class="fas fa-calendar w-8 text-gray-400"></i>
                    <span>Embauché le {{ $agent->date_embauche->format('d/m/Y') }}</span>
                </div>
                <div class="flex items-center">
                    <i class="fas fa-users w-8 text-gray-400"></i>
                    <span>{{ number_format($agent->producteurs_enregistres) }} producteurs</span>
                </div>
                <div class="flex items-center">
                    <i class="fas fa-chalkboard-user w-8 text-gray-400"></i>
                    <span>Superviseur: {{ $agent->superviseur->nom_complet ?? 'Aucun' }}</span>
                </div>
                <div class="flex items-center">
                    <i class="fas fa-tag w-8 text-gray-400"></i>
                    <span class="px-2 py-1 text-xs rounded-full {{ $agent->statut == 'actif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $agent->statut }}
                    </span>
                </div>
            </div>
            
            <div class="mt-6 pt-4 border-t flex justify-between">
                <a href="{{ route('admin.agents.edit', $agent) }}" class="text-green-600 hover:underline">
                    <i class="fas fa-edit mr-1"></i>Modifier
                </a>
                <!-- -----------------------------------
                Appel avec id et nom
                ----------------------------------- -->
                <button onclick="openResetModal({{ $agent->id }}, '{{ addslashes($agent->nom_complet) }}')" 
                        class="text-orange-600 hover:underline">
                    <i class="fas fa-key mr-1"></i>Réinitialiser mot de passe
                </button>
                <form action="{{ route('admin.agents.destroy', $agent) }}" method="POST" class="inline delete-confirm">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-600 hover:underline">
                        <i class="fas fa-trash mr-1"></i>Supprimer
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Actions rapides -->
        <div class="bg-white rounded-xl shadow-sm p-6 mt-6">
            <h4 class="font-semibold text-dark mb-3">Actions rapides</h4>
            <div class="space-y-2">
                <a href="{{ route('admin.producteurs.create', ['agent_terrain_id' => $agent->id]) }}" class="flex items-center text-primary hover:underline">
                    <i class="fas fa-plus-circle w-6"></i>
                    <span>Ajouter un producteur</span>
                </a>
                <a href="{{ route('admin.suivi.create', ['agent_id' => $agent->id]) }}" class="flex items-center text-primary hover:underline">
                    <i class="fas fa-clipboard-list w-6"></i>
                    <span>Nouveau suivi terrain</span>
                </a>
                <a href="{{ route('admin.producteurs.index', ['agent_id' => $agent->id]) }}" class="flex items-center text-primary hover:underline">
                    <i class="fas fa-users w-6"></i>
                    <span>Voir ses producteurs</span>
                </a>
            </div>
        </div>
    </div>
    
    <!-- Liste des producteurs -->
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">Producteurs enregistrés</h3>
                <span class="text-sm text-gray-500">Total: {{ number_format($agent->producteurs_enregistres) }}</span>
            </div>
            
            @if($producteurs && $producteurs->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs">Code</th>
                            <th class="px-4 py-2 text-left text-xs">Nom</th>
                            <th class="px-4 py-2 text-left text-xs">Contact</th>
                            <th class="px-4 py-2 text-left text-xs">Région</th>
                            <th class="px-4 py-2 text-left text-xs">Culture</th>
                            <th class="px-4 py-2 text-center text-xs">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach($producteurs as $producteur)
                        <tr>
                            <td class="px-4 py-2 text-sm">{{ $producteur->code_producteur }}</td>
                            <td class="px-4 py-2">{{ $producteur->nom_complet }}</td>
                            <td class="px-4 py-2 text-sm">{{ $producteur->contact }}</td>
                            <td class="px-4 py-2 text-sm">{{ $producteur->region }}</td>
                            <td class="px-4 py-2 text-sm">{{ $producteur->culture_pratiquee }}</td>
                            <td class="px-4 py-2 text-center">
                                <a href="{{ route('admin.producteurs.show', $producteur) }}" class="text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($producteurs->count() > 10)
            <div class="mt-4 text-center">
                <a href="{{ route('admin.producteurs.index', ['agent_id' => $agent->id]) }}" class="text-primary hover:underline">
                    Voir tous les producteurs →
                </a>
            </div>
            @endif
            @else
            <div class="text-center py-8 text-gray-500">
                <i class="fas fa-users text-4xl mb-2 opacity-50"></i>
                <p>Aucun producteur enregistré par cet agent</p>
                <a href="{{ route('admin.producteurs.create', ['agent_terrain_id' => $agent->id]) }}" class="inline-block mt-2 text-primary hover:underline">
                    + Ajouter un producteur
                </a>
            </div>
            @endif
        </div>
        
        <!-- Dernières activités -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold mb-4">Dernières activités</h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div>
                        <p class="font-medium">Dernière connexion</p>
                        <p class="text-xs text-gray-500">{{ $agent->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <i class="fas fa-clock text-gray-400"></i>
                </div>
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div>
                        <p class="font-medium">Dernier producteur ajouté</p>
                        <p class="text-xs text-gray-500">
                            @if($producteurs && $producteurs->first())
                                {{ $producteurs->first()->nom_complet }} - {{ $producteurs->first()->created_at->diffForHumans() }}
                            @else
                                Aucun producteur
                            @endif
                        </p>
                    </div>
                    <i class="fas fa-user-plus text-gray-400"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Réinitialisation mot de passe -->
<div id="resetModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl p-6 max-w-md w-full">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold">Réinitialiser le mot de passe</h3>
            <button onclick="closeResetModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <p id="resetMessage" class="text-gray-600 mb-4">Définir un nouveau mot de passe pour <strong></strong></p>
        
        <form id="resetForm" method="POST" action="">
            @csrf
            
            <div class="mb-4">
                <label class="block text-sm font-semibold mb-2">Nouveau mot de passe</label>
                <div class="relative">
                    <input type="password" name="password" id="new_password" required 
                           class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary pr-10">
                    <button type="button" onclick="togglePassword('new_password', 'eye1')" 
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-primary">
                        <i id="eye1" class="fas fa-eye-slash"></i>
                    </button>
                </div>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-semibold mb-2">Confirmer le mot de passe</label>
                <div class="relative">
                    <input type="password" name="password_confirmation" id="password_confirmation" required 
                           class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary pr-10">
                    <button type="button" onclick="togglePassword('password_confirmation', 'eye2')" 
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-primary">
                        <i id="eye2" class="fas fa-eye-slash"></i>
                    </button>
                </div>
            </div>
            
            <div class="flex justify-end space-x-3 mt-6">
                <button type="button" onclick="closeResetModal()" class="px-4 py-2 border rounded-lg hover:bg-gray-50">Annuler</button>
                <button type="submit" class="bg-orange-500 text-white px-4 py-2 rounded-lg hover:bg-orange-600">
                    <i class="fas fa-key mr-2"></i>Réinitialiser
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openResetModal(id, name) {
        const modal = document.getElementById('resetModal');
        const message = document.getElementById('resetMessage');
        const form = document.getElementById('resetForm');
        
        if (!modal || !message || !form) {
            console.error('Modal elements not found');
            return;
        }
        
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        
        // Afficher le nom correctement
        if (name && name !== 'undefined') {
            message.innerHTML = 'Définir un nouveau mot de passe pour <strong>' + name + '</strong>';
        } else {
            message.innerHTML = 'Définir un nouveau mot de passe pour l\'agent';
        }
        
        // Définir l'action du formulaire
        form.action = "/admin/agents/" + id + "/reset-password";
        
        // Réinitialiser les champs
        const newPassword = document.getElementById('new_password');
        const confirmPassword = document.getElementById('password_confirmation');
        if (newPassword) newPassword.value = '';
        if (confirmPassword) confirmPassword.value = '';
    }
    
    function closeResetModal() {
        const modal = document.getElementById('resetModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }
    
    function togglePassword(inputId, eyeId) {
        const input = document.getElementById(inputId);
        const eye = document.getElementById(eyeId);
        
        if (!input || !eye) return;
        
        if (input.type === 'password') {
            input.type = 'text';
            eye.classList.remove('fa-eye-slash');
            eye.classList.add('fa-eye');
        } else {
            input.type = 'password';
            eye.classList.remove('fa-eye');
            eye.classList.add('fa-eye-slash');
        }
    }
    
    // Fermer le modal en cliquant en dehors
    document.getElementById('resetModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeResetModal();
        }
    });
</script>
@endsection