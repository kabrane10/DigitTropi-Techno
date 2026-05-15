@extends('layouts.admin')

@section('title', 'Distributions d\'intrants')
@section('header', 'Gestion des distributions d\'intrants')

@section('content')
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b flex justify-between items-center flex-wrap gap-4">
        <h2 class="text-xl font-semibold">Liste des distributions d'intrants</h2>
        <div class="flex space-x-3">
            <a href="{{ route('admin.distributions-intrants.create') }}" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-secondary transition">
                <i class="fas fa-plus mr-2"></i>Nouvelle distribution
            </a>
        </div>
    </div>
    
    <!-- Filtres -->
    <div class="p-6 border-b bg-gray-50">
        <form method="GET" class="flex flex-wrap gap-4 items-end">
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1">Bénéficiaire</label>
                <select name="beneficiaire_type" class="px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    <option value="">Tous</option>
                    <option value="producteur" {{ request('beneficiaire_type') == 'producteur' ? 'selected' : '' }}> Producteurs</option>
                    <option value="cooperative" {{ request('beneficiaire_type') == 'cooperative' ? 'selected' : '' }}> Coopératives</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1">Intrant</label>
                <select name="intrant_id" class="px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    <option value="">Tous les intrants</option>
                    @foreach($intrants as $intrant)
                    <option value="{{ $intrant->id }}" {{ request('intrant_id') == $intrant->id ? 'selected' : '' }}>
                        {{ $intrant->nom }} ({{ $intrant->type_label }})
                    </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1">Zone</label>
                <select name="zone" class="px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    <option value="">Toutes les zones</option>
                    @foreach($zones as $zone)
                    <option value="{{ $zone }}" {{ request('zone') == $zone ? 'selected' : '' }}> {{ $zone }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-secondary transition">
                <i class="fas fa-search mr-2"></i>Filtrer
            </button>
            @if(request()->anyFilled(['beneficiaire_type', 'intrant_id', 'zone']))
            <a href="{{ route('admin.distributions-intrants.index') }}" class="text-gray-500 hover:text-gray-700 px-4 py-2">
                <i class="fas fa-times mr-1"></i>Effacer
            </a>
            @endif
        </form>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Code</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Bénéficiaire</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Intrant</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Quantité</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Montant</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Zone</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($distributions as $distribution)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4">
                        <span class="text-sm font-mono text-blue-600 font-semibold">{{ $distribution->code_distribution }}</span>
                    </td>
                    <td class="px-6 py-4 text-sm">{{ $distribution->date_distribution->format('d/m/Y') }}</td>
                    <td class="px-6 py-4">
                        @if($distribution->cooperative_id || $distribution->beneficiaire_type === 'App\\Models\\Cooperative')
                            <div class="flex items-center">
                                <i class="fas fa-handshake text-purple-600 mr-2"></i>
                                <div>
                                    <p class="font-medium text-gray-800">{{ $distribution->cooperative->nom ?? 'N/A' }}</p>
                                    <p class="text-xs text-gray-500">Coopérative • {{ $distribution->cooperative->code_cooperative ?? '' }}</p>
                                </div>
                            </div>
                        @else
                            <div class="flex items-center">
                                <i class="fas fa-user text-green-600 mr-2"></i>
                                <div>
                                    <p class="font-medium text-gray-800">{{ $distribution->producteur->nom_complet ?? 'N/A' }}</p>
                                    <p class="text-xs text-gray-500">Producteur • {{ $distribution->producteur->code_producteur ?? '' }}</p>
                                </div>
                            </div>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-2">
                                <i class="fas fa-flask text-blue-600 text-xs"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium">{{ $distribution->intrant->nom }}</p>
                                <p class="text-xs text-gray-400">{{ $distribution->intrant->type_label }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ number_format($distribution->quantite, 2) }} {{ $distribution->intrant->unite }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right font-semibold text-green-600">
                        {{ number_format($distribution->montant_total, 0, ',', ' ') }} CFA
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full 
                            @if($distribution->zone == 'Centrale') bg-blue-100 text-blue-700
                            @elseif($distribution->zone == 'Kara') bg-green-100 text-green-700
                            @else bg-yellow-100 text-yellow-700
                            @endif">
                            <i class="fas fa-map-marker-alt mr-1"></i>{{ $distribution->zone }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center space-x-2">
                        <a href="{{ route('admin.distributions-intrants.show', $distribution) }}" 
                           class="inline-flex items-center justify-center w-8 h-8 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition" 
                           title="Voir">
                            <i class="fas fa-eye text-sm"></i>
                        </a>
                        <a href="{{ route('admin.distributions-intrants.print', $distribution) }}" target="_blank"
                           class="inline-flex items-center justify-center w-8 h-8 bg-gray-50 text-gray-600 rounded-lg hover:bg-gray-100 transition" 
                           title="Imprimer">
                            <i class="fas fa-print text-sm"></i>
                        </a>
                        <form action="{{ route('admin.distributions-intrants.destroy', $distribution) }}" method="POST" class="inline delete-confirm">
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
                            <i class="fas fa-flask text-5xl text-gray-300 mb-3"></i>
                            <p class="text-gray-400 font-medium">Aucune distribution d'intrants trouvée</p>
                            <p class="text-gray-400 text-sm mt-1">Commencez par créer une nouvelle distribution</p>
                            <a href="{{ route('admin.distributions-intrants.create') }}" class="mt-4 bg-primary text-white px-4 py-2 rounded-lg hover:bg-secondary transition">
                                <i class="fas fa-plus mr-2"></i>Nouvelle distribution
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="p-6 border-t">
        {{ $distributions->links() }}
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.querySelectorAll('.delete-confirm').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Confirmation',
                text: 'Êtes-vous sûr de vouloir supprimer cette distribution ?',
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