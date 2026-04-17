@extends('layouts.admin')

@section('title', 'Crédits agricoles')
@section('header', 'Gestion des crédits agricoles')

@section('content')
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b flex justify-between items-center flex-wrap gap-4">
        <h2 class="text-xl font-semibold">Liste des crédits</h2>
        <div class="flex space-x-3">
            <a href="{{ route('admin.credits.create') }}" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-secondary">
                <i class="fas fa-plus mr-2"></i>Nouveau crédit
            </a>
            <a href="{{ route('admin.rapports.export-credits', request()->all()) }}" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600">
                <i class="fas fa-file-excel mr-2"></i>Exporter Excel
            </a>
        </div>
    </div>
    
    <!-- Filtres -->
    <div class="p-6 border-b bg-gray-50">
        <form method="GET" class="flex flex-wrap gap-4">
            <select name="statut" class="px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                <option value="">Tous les statuts</option>
                <option value="actif" {{ request('statut') == 'actif' ? 'selected' : '' }}>Actif</option>
                <option value="rembourse" {{ request('statut') == 'rembourse' ? 'selected' : '' }}>Remboursé</option>
                <option value="impaye" {{ request('statut') == 'impaye' ? 'selected' : '' }}>Impayé</option>
            </select>
            <select name="producteur_id" class="px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                <option value="">Tous les producteurs</option>
                @foreach($producteurs as $producteur)
                <option value="{{ $producteur->id }}" {{ request('producteur_id') == $producteur->id ? 'selected' : '' }}>
                    {{ $producteur->nom_complet }}
                </option>
                @endforeach
            </select>
            <button type="submit" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-secondary">
                <i class="fas fa-search mr-2"></i>Filtrer
            </button>
        </form>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Code</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Producteur</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500">Montant total</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500">Reste à payer</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Échéance</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Statut</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($credits as $credit)
                <tr>
                    <td class="px-6 py-4 text-sm font-mono">{{ $credit->code_credit }}</td>
                    <td class="px-6 py-4 font-medium">{{ $credit->producteur->nom_complet }}</td>
                    <td class="px-6 py-4 text-right text-sm">{{ number_format($credit->montant_total, 0, ',', ' ') }} CFA</td>
                    <td class="px-6 py-4 text-right text-sm {{ $credit->montant_restant > 0 ? 'text-red-600 font-semibold' : 'text-green-600' }}">
                        {{ number_format($credit->montant_restant, 0, ',', ' ') }} CFA
                    </td>
                    <td class="px-6 py-4 text-sm {{ $credit->est_en_retard ? 'text-red-600 font-semibold' : '' }}">
                        {{ $credit->date_echeance->format('d/m/Y') }}
                        @if($credit->est_en_retard)
                            <span class="ml-1 text-xs bg-red-100 text-red-800 px-1 py-0.5 rounded">Retard</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full 
                            @if($credit->statut == 'actif') bg-yellow-100 text-yellow-800
                            @elseif($credit->statut == 'rembourse') bg-green-100 text-green-800
                            @else bg-red-100 text-red-800
                            @endif">
                            {{ $credit->statut }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center space-x-2">
                        <a href="{{ route('admin.credits.show', $credit) }}" class="text-blue-600 hover:text-blue-800" title="Voir">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('admin.credits.edit', $credit) }}" class="text-green-600 hover:text-green-800" title="Modifier">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.credits.destroy', $credit) }}" method="POST" class="inline" id="delete-form-{{ $credit->id }}">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="text-red-600 hover:text-red-800 delete-credit-btn" 
                                    data-id="{{ $credit->id }}" 
                                    data-code="{{ $credit->code_credit }}"
                                    title="Supprimer">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                        <a href="{{ route('admin.credits.print', $credit) }}" target="_blank" class="text-gray-600 hover:text-gray-800" title="Imprimer">
                            <i class="fas fa-print"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="p-6">
        {{ $credits->links() }}
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.querySelectorAll('.delete-credit-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const creditId = this.dataset.id;
            const creditCode = this.dataset.code;
            const form = document.getElementById(`delete-form-${creditId}`);
            
            Swal.fire({
                title: '⚠️ Supprimer le crédit ?',
                html: `Vous allez supprimer le crédit <strong>${creditCode}</strong><br><br>Cette action est irréversible.`,
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
@endsection