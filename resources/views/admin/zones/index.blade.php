@extends('layouts.admin')

@section('title', 'Gestion des Zones de Stockage')
@section('header', ' Zones de Stockage')

@push('styles')
{{-- SweetAlert2 pour des alertes modernes --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@section('content')
<div class="space-y-6">
    {{-- En-tête de la Page --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-dark tracking-tight">Zones de Stockage</h1>
            <p class="text-sm text-gray-500">Gérez les lieux où les intrants sont stockés.</p>
        </div>
        <a href="{{ route('admin.zones.create') }}" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-secondary transition flex items-center shadow-sm font-semibold self-start sm:self-center">
            <i class="fas fa-plus mr-2 text-sm"></i>
            <span>Ajouter une Zone</span>
        </a>
    </div>

    {{-- Alertes Flash épurées --}}
    @if(session('success'))
    <div class="alert bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-sm flex items-center justify-between transition-all duration-300" role="alert">
        <div class="flex items-center">
            <i class="fas fa-check-circle mr-3 text-green-500 text-lg"></i>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
        <button type="button" onclick="this.parentElement.remove()" class="text-green-500 hover:text-green-700 font-bold text-xl leading-none">&times;</button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-lg shadow-sm flex items-center justify-between transition-all duration-300" role="alert">
        <div class="flex items-center">
            <i class="fas fa-exclamation-circle mr-3 text-red-500 text-lg"></i>
            <span class="text-sm font-medium">{{ session('error') }}</span>
        </div>
        <button type="button" onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700 font-bold text-xl leading-none">&times;</button>
    </div>
    @endif

    {{-- Conteneur Principal / Tableau --}}
    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-white">
            <h3 class="text-base font-semibold text-dark">Répertoire des emplacements</h3>
            <span class="text-xs font-semibold text-gray-500 bg-gray-100 px-2.5 py-1 rounded-full">
                {{ $zones->count() }} zone(s) au total
            </span>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                            Nom de la Zone
                        </th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">
                            Intrants rattachés
                        </th>
                        <th scope="col" class="px-6 py-3 text-right pr-8 text-xs font-bold text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($zones as $zone)
                    <tr class="hover:bg-gray-50 transition-colors">
                        {{-- Nom de la Zone --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-gray-50 text-gray-400 rounded-lg p-2.5 flex items-center justify-center h-10 w-10 border border-gray-100">
                                    <i class="fas fa-warehouse text-gray-400"></i>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-semibold text-dark">{{ $zone->name }}</div>
                                </div>
                            </div>
                        </td>
                        
                        {{-- Compteur d'intrants --}}
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($zone->intrant_stocks_count > 0)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700">
                                <i class="fas fa-box mr-1.5 text-xs"></i> {{ $zone->intrant_stocks_count }} produit(s)
                            </span>
                            @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-500">
                                Vide
                            </span>
                            @endif
                        </td>
                        
                        {{-- Actions Épurées --}}
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium pr-8">
                            <div class="flex items-center justify-end space-x-2">
                                <a href="{{ route('admin.zones.edit', $zone->id) }}" 
                                   class="text-blue-600 hover:bg-blue-50 p-2 rounded-lg transition-colors"
                                   title="Modifier l'emplacement">
                                    <i class="fas fa-pencil-alt text-base"></i>
                                </a>
                                <button onclick="confirmDelete('{{ $zone->id }}')" 
                                        class="text-red-600 hover:bg-red-50 p-2 rounded-lg transition-colors"
                                        title="Supprimer la zone">
                                    <i class="fas fa-trash-alt text-base"></i>
                                </button>
                            </div>

                            <form id="delete-form-{{ $zone->id }}" action="{{ route('admin.zones.destroy', $zone->id) }}" method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                    @empty
                    {{-- Empty State Moderne --}}
                    <tr>
                        <td colspan="3" class="px-6 py-16 text-center bg-white">
                            <div class="flex flex-col items-center justify-center max-w-sm mx-auto">
                                <div class="bg-gray-50 rounded-full p-4 mb-4 flex items-center justify-center text-gray-400 w-16 h-16 border border-gray-100">
                                    <i class="fas fa-map-marked-alt text-xl"></i>
                                </div>
                                <h5 class="text-base font-semibold text-dark mb-1">Aucune zone de stockage</h5>
                                <p class="text-sm text-gray-500 mb-4">Vous n'avez pas encore configuré d'entrepôt ou d'espace de stockage.</p>
                                <a href="{{ route('admin.zones.create') }}" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-secondary transition shadow-sm text-sm font-semibold">
                                    Créer un premier espace
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Boîte de dialogue de confirmation réadaptée aux couleurs de l'application
    function confirmDelete(zoneId) {
        Swal.fire({
            title: 'Supprimer cette zone ?',
            text: "Attention, si des stocks y sont actuellement rattachés, l'opération sera annulée.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444', // Rouge pur Tailwind
            cancelButtonColor: '#6b7280',  // Gris neutre Tailwind
            confirmButtonText: 'Oui, supprimer',
            cancelButtonText: 'Annuler',
            focusCancel: true,
            customClass: {
                popup: 'rounded-xl'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + zoneId).submit();
            }
        })
    }

    // Auto-dismiss en JavaScript natif (Vanilla JS) pour éviter les dépendances jQuery obsolètes
    window.setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            alert.style.transition = "opacity 0.4s ease, transform 0.4s ease";
            alert.style.opacity = "0";
            setTimeout(() => alert.remove(), 400);
        });
    }, 4500);
</script>
@endpush