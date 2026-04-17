@extends('layouts.animateur')

@section('title', 'Mes agents')
@section('header', 'Gestion des agents')

@section('content')
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-4 sm:p-6 border-b flex justify-between items-center flex-wrap gap-4">
        <h2 class="text-xl font-semibold">Liste de mes agents</h2>
        <a href="{{ route('animateur.agents.create') }}" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-secondary">
            <i class="fas fa-plus mr-2"></i>Nouvel agent
        </a>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs">Code</th>
                    <th class="px-4 py-3 text-left text-xs">Nom</th>
                    <th class="px-4 py-3 text-left text-xs">Contact</th>
                    <th class="px-4 py-3 text-left text-xs">Zone</th>
                    <th class="px-4 py-3 text-right text-xs">Producteurs</th>
                    <th class="px-4 py-3 text-left text-xs">Statut</th>
                    <th class="px-4 py-3 text-center text-xs">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($agents as $agent)
                <tr>
                    <td class="px-4 py-3 text-sm">{{ $agent->code_agent }}</td>
                    <td class="px-4 py-3 font-medium">{{ $agent->nom_complet }}</td>
                    <td class="px-4 py-3 text-sm">{{ $agent->telephone }}</td>
                    <td class="px-4 py-3 text-sm">{{ $agent->zone_affectation }}</td>
                    <td class="px-4 py-3 text-right text-sm">{{ number_format($agent->producteurs_enregistres) }}</td>
                    <td class="px-4 py-3"><span class="px-2 py-1 text-xs rounded-full {{ $agent->statut == 'actif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">{{ $agent->statut }}</span></td>
                    <td class="px-4 py-3 text-center space-x-2">
                        <a href="{{ route('animateur.agents.show', $agent) }}" class="text-blue-600"><i class="fas fa-eye"></i></a>
                        <a href="{{ route('animateur.agents.edit', $agent) }}" class="text-green-600"><i class="fas fa-edit"></i></a>
                        <button onclick="openResetModal({{ $agent->id }}, '{{ addslashes($agent->nom_complet) }}')" class="text-orange-600"><i class="fas fa-key"></i></button>
                        <form action="{{ route('animateur.agents.destroy', $agent) }}" method="POST" class="inline delete-confirm">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="p-4">{{ $agents->links() }}</div>
</div>

<!-- Modal Réinitialisation -->
<div id="resetModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl p-6 max-w-md w-full mx-4">
        <h3 class="text-xl font-bold mb-4">Réinitialiser le mot de passe</h3>
        <p id="resetMessage" class="text-gray-600 mb-6">Nouveau mot de passe pour <strong></strong></p>
        <form id="resetForm" method="POST" action="">
            @csrf
            <div class="mb-4"><input type="password" name="password" placeholder="Nouveau mot de passe" class="w-full px-3 py-2 border rounded-lg"></div>
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeResetModal()" class="px-4 py-2 border rounded-lg">Annuler</button>
                <button type="submit" class="bg-orange-500 text-white px-4 py-2 rounded-lg">Réinitialiser</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openResetModal(id, name) {
        document.getElementById('resetModal').classList.remove('hidden');
        document.getElementById('resetModal').classList.add('flex');
        document.getElementById('resetMessage').innerHTML = 'Nouveau mot de passe pour <strong>' + name + '</strong>';
        document.getElementById('resetForm').action = "/animateur/agents/" + id + "/reset-password";
    }
    function closeResetModal() {
        document.getElementById('resetModal').classList.add('hidden');
        document.getElementById('resetModal').classList.remove('flex');
    }
</script>
@endsection