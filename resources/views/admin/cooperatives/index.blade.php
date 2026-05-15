@extends('layouts.admin')

@section('title', 'Coopératives')
@section('header', 'Gestion des coopératives')

@section('content')
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b flex justify-between items-center flex-wrap gap-4">
        <h2 class="text-xl font-semibold">Liste des coopératives</h2>
        <div class="flex space-x-3">
            <a href="{{ route('admin.cooperatives.export') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition">
                <i class="fas fa-download mr-2"></i>Exporter
            </a>
            <a href="{{ route('admin.cooperatives.create') }}" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-secondary transition">
                <i class="fas fa-plus mr-2"></i>Nouvelle coopérative
            </a>
        </div>
    </div>
    
    <!-- Filtres -->
    <div class="p-6 border-b bg-gray-50">
        <form method="GET" class="flex flex-wrap gap-4 items-end">
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1">Région</label>
                <select name="region" class="px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    <option value="">Toutes régions</option>
                    <option value="Centrale" {{ request('region') == 'Centrale' ? 'selected' : '' }}> Centrale</option>
                    <option value="Kara" {{ request('region') == 'Kara' ? 'selected' : '' }}> Kara</option>
                    <option value="Savanes" {{ request('region') == 'Savanes' ? 'selected' : '' }}> Savanes</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1">Statut</label>
                <select name="statut" class="px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    <option value="">Tous statuts</option>
                    <option value="active" {{ request('statut') == 'active' ? 'selected' : '' }}> Active</option>
                    <option value="suspendue" {{ request('statut') == 'suspendue' ? 'selected' : '' }}> Suspendue</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1">Recherche</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Nom, responsable, code..."
                       class="px-4 py-2 border rounded-lg w-64 focus:outline-none focus:border-primary">
            </div>
            <button type="submit" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-secondary transition">
                <i class="fas fa-search mr-2"></i>Filtrer
            </button>
            @if(request()->anyFilled(['region', 'statut', 'search']))
            <a href="{{ route('admin.cooperatives.index') }}" class="text-gray-500 hover:text-gray-700 px-4 py-2">
                <i class="fas fa-times mr-1"></i>Effacer
            </a>
            @endif
        </form>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <!-- <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Code</th> -->
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Coopérative</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Responsable</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Contact</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Région</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Membres</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Statut</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($cooperatives as $cooperative)
                <tr class="hover:bg-gray-50 transition">
                    <!-- <td class="px-6 py-4">
                        <span class="text-sm font-mono text-purple-600 font-semibold">{{ $cooperative->code_cooperative }}</span>
                    </td> -->
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-handshake text-purple-600 text-xs"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800">{{ $cooperative->nom }}</p>
                                <p class="text-xs text-gray-400">{{ $cooperative->commune ?? 'Commune non définie' }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-sm">{{ $cooperative->nom_responsable ?? '—' }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-sm">{{ $cooperative->contact }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full 
                            @if($cooperative->region == 'Centrale') bg-blue-100 text-blue-700
                            @elseif($cooperative->region == 'Kara') bg-green-100 text-green-700
                            @else bg-yellow-100 text-yellow-700
                            @endif">
                            {{ $cooperative->region }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            <i class="fas fa-users mr-1 text-xs"></i>
                            {{ number_format($cooperative->producteurs_count ?? $cooperative->producteurs->count()) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full {{ $cooperative->statut == 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            <i class="fas {{ $cooperative->statut == 'active' ? 'fa-check-circle' : 'fa-times-circle' }} mr-1 text-xs"></i>
                            {{ $cooperative->statut == 'active' ? 'Active' : 'Suspendue' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center space-x-2">
                        <a href="{{ route('admin.cooperatives.show', $cooperative) }}" 
                           class="inline-flex items-center justify-center w-8 h-8 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition" 
                           title="Voir">
                            <i class="fas fa-eye text-sm"></i>
                        </a>
                        <a href="{{ route('admin.cooperatives.operations.test.dashboard', $cooperative) }}" 
                           class="inline-flex items-center justify-center w-8 h-8 bg-purple-50 text-purple-600 rounded-lg hover:bg-purple-100 transition" 
                           title="Opérations">
                            <i class="fas fa-chart-line text-sm"></i>
                        </a>
                        <a href="{{ route('admin.cooperatives.edit', $cooperative) }}" 
                           class="inline-flex items-center justify-center w-8 h-8 bg-green-50 text-green-600 rounded-lg hover:bg-green-100 transition" 
                           title="Modifier">
                            <i class="fas fa-edit text-sm"></i>
                        </a>
                        <form action="{{ route('admin.cooperatives.destroy', $cooperative) }}" method="POST" class="inline delete-confirm">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center justify-center w-8 h-8 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition" title="Supprimer">
                                <i class="fas fa-trash text-sm"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <i class="fas fa-handshake text-5xl text-gray-300 mb-3"></i>
                            <p class="text-gray-400 font-medium">Aucune coopérative trouvée</p>
                            <p class="text-gray-400 text-sm mt-1">Commencez par créer une nouvelle coopérative</p>
                            <a href="{{ route('admin.cooperatives.create') }}" class="mt-4 bg-primary text-white px-4 py-2 rounded-lg hover:bg-secondary transition">
                                <i class="fas fa-plus mr-2"></i>Créer une coopérative
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="p-6 border-t">
        {{ $cooperatives->links() }}
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Confirmation de suppression
    document.querySelectorAll('.delete-confirm').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Confirmation',
                text: 'Êtes-vous sûr de vouloir supprimer cette coopérative ?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Oui, supprimer',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endpush
@endsection