@extends('layouts.admin')

@section('title', 'Agents terrain')
@section('header', 'Gestion des agents terrain')

@section('content')
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b flex justify-between items-center">
        <h2 class="text-xl font-semibold">Liste des agents terrain</h2>
        <a href="{{ route('admin.agents.create') }}" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-secondary">
            <i class="fas fa-plus mr-2"></i>Nouvel agent
        </a>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Code</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Nom</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Contact</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Zone</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Superviseur</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Statut</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
            @foreach($agents as $agent)
<tr>
    <td class="px-6 py-4 text-sm">{{ $agent->code_agent }}</td>
    <td class="px-6 py-4 font-medium">{{ $agent->nom_complet }}</td>
    <td class="px-6 py-4 text-sm">{{ $agent->telephone }}</td>
    <td class="px-6 py-4 text-sm">{{ $agent->zone_affectation }}</td>
    <td class="px-6 py-4 text-sm">{{ $agent->superviseur->nom_complet ?? '-' }}</td>
    <td class="px-6 py-4">
        <span class="px-2 py-1 text-xs rounded-full {{ $agent->statut == 'actif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
            {{ $agent->statut }}
        </span>
    </td>
    <td class="px-6 py-4 space-x-2">
                        <a href="{{ route('admin.agents.show', $agent) }}" class="text-blue-600"><i class="fas fa-eye"></i></a>
                        <a href="{{ route('admin.agents.edit', $agent) }}" class="text-green-600"><i class="fas fa-edit"></i></a>
                        <button onclick="openResetModal({{ $agent->id }}, '{{ $agent->nom_complet }}')" class="text-orange-600"><i class="fas fa-key"></i></button>
                        <form action="{{ route('admin.agents.destroy', $agent) }}" method="POST" class="inline delete-confirm">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="p-6">
        {{ $agents->links() }}
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
            
            <!-- Nouveau mot de passe -->
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
            
            <!-- Confirmation mot de passe -->
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
        
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        message.innerHTML = 'Définir un nouveau mot de passe pour <strong>' + name + '</strong>';
        form.action = "/admin/agents/" + id + "/reset-password";
        
        // Réinitialiser les champs
        document.getElementById('new_password').value = '';
        document.getElementById('password_confirmation').value = '';
    }
    
    function closeResetModal() {
        document.getElementById('resetModal').classList.add('hidden');
        document.getElementById('resetModal').classList.remove('flex');
    }
    
    function togglePassword(inputId, eyeId) {
        const input = document.getElementById(inputId);
        const eye = document.getElementById(eyeId);
        
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
    document.getElementById('resetModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeResetModal();
        }
    });
</script>

@endsection