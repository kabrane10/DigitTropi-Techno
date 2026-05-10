@extends('layouts.admin')

@section('title', 'Gestion du stock')
@section('header', 'Stock - ' . $stock->intrant->nom . ' (' . $stock->zone . ')')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Cartes d'information -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-primary">
            <p class="text-gray-500 text-sm">Stock actuel</p>
            <p class="text-2xl font-bold {{ $stock->est_critique ? 'text-red-600' : 'text-green-600' }}">
                {{ number_format($stock->stock_actuel) }} {{ $stock->unite }}
            </p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-yellow-500">
            <p class="text-gray-500 text-sm">Seuil d'alerte</p>
            <p class="text-2xl font-bold">{{ number_format($stock->seuil_alerte) }} {{ $stock->unite }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-green-500">
            <p class="text-gray-500 text-sm">Unité</p>
            <p class="text-2xl font-bold">{{ $stock->unite }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-blue-500">
            <p class="text-gray-500 text-sm">Emplacement</p>
            <p class="text-xl font-bold">{{ $stock->emplacement ?? 'Non défini' }}</p>
        </div>
    </div>
    
    <!-- Actions d'ajout/retrait -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Ajouter du stock -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold mb-4 text-green-600">
                <i class="fas fa-plus-circle mr-2"></i>Ajouter du stock
            </h3>
            <form action="{{ route('admin.intrants.ajouter-stock', ['intrant' => $stock->intrant_id, 'zone' => $stock->zone]) }}" method="POST" id="formAjout">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold mb-2">Quantité à ajouter ({{ $stock->unite }}) *</label>
                        <input type="number" step="0.01" name="quantite" id="quantiteAjout" required 
                               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-2">Motif *</label>
                        <select name="motif" id="motifAjout" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                            <option value="Achat"> Achat</option>
                            <option value="Réapprovisionnement"> Réapprovisionnement</option>
                            <option value="Don"> Don</option>
                            <option value="Transfert"> Transfert</option>
                            <option value="Inventaire"> Inventaire</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-2">Référence</label>
                        <div class="flex gap-2">
                            <input type="text" name="reference" id="referenceAjout" 
                                   class="flex-1 px-4 py-2 border rounded-lg bg-gray-50 focus:outline-none focus:border-primary"
                                   placeholder="Générée automatiquement" readonly>
                            <button type="button" onclick="genererReference('ajout')" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
                                <i class="fas fa-sync-alt mr-1"></i>
                            </button>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Référence unique pour le suivi</p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-2">Notes</label>
                        <textarea name="notes" rows="2" class="w-full px-4 py-2 border rounded-lg"></textarea>
                    </div>
                    <button type="submit" class="w-full bg-green-500 text-white py-2 rounded-lg hover:bg-green-600">
                        <i class="fas fa-save mr-2"></i>Ajouter au stock
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Retirer du stock -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold mb-4 text-orange-600">
                <i class="fas fa-minus-circle mr-2"></i>Retirer du stock
            </h3>
            <form action="{{ route('admin.intrants.retirer-stock', ['intrant' => $stock->intrant_id, 'zone' => $stock->zone]) }}" method="POST" id="formRetrait">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold mb-2">Quantité à retirer ({{ $stock->unite }}) *</label>
                        <input type="number" step="0.01" name="quantite" id="quantiteRetrait" required 
                               max="{{ $stock->stock_actuel }}"
                               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                        <p class="text-xs text-gray-500 mt-1">Maximum: {{ number_format($stock->stock_actuel) }} {{ $stock->unite }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-2">Motif *</label>
                        <select name="motif" id="motifRetrait" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                            <option value="Utilisation"> Utilisation terrain</option>
                            <option value="Distribution"> Distribution producteurs</option>
                            <option value="Péremption">⚠️ Péremption</option>
                            <option value="Perte"> Perte</option>
                            <option value="Transfert"> Transfert</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-2">Référence</label>
                        <div class="flex gap-2">
                            <input type="text" name="reference" id="referenceRetrait" 
                                   class="flex-1 px-4 py-2 border rounded-lg bg-gray-50 focus:outline-none focus:border-primary"
                                   placeholder="Générée automatiquement" readonly>
                            <button type="button" onclick="genererReference('retrait')" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
                                <i class="fas fa-sync-alt mr-1"></i>
                            </button>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Référence unique pour le suivi</p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-2">Notes</label>
                        <textarea name="notes" rows="2" class="w-full px-4 py-2 border rounded-lg"></textarea>
                    </div>
                    <button type="submit" class="w-full bg-orange-500 text-white py-2 rounded-lg hover:bg-orange-600" 
                            {{ $stock->stock_actuel <= 0 ? 'disabled' : '' }}>
                        <i class="fas fa-minus-circle mr-2"></i>Retirer du stock
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Historique des mouvements -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-semibold mb-4"> Historique des mouvements</h3>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left">Date</th>
                        <th class="px-4 py-2 text-center">Type</th>
                        <th class="px-4 py-2 text-right">Quantité</th>
                        <th class="px-4 py-2 text-left">Motif</th>
                        <th class="px-4 py-2 text-left">Référence</th>
                        <th class="px-4 py-2 text-left">Utilisateur</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($mouvements as $mvt)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2 text-sm">{{ $mvt->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-4 py-2 text-center">
                            <span class="px-2 py-1 text-xs rounded-full {{ $mvt->type == 'entree' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $mvt->type == 'entree' ? '➕ Entrée' : '➖ Sortie' }}
                            </span>
                        </td>
                        <td class="px-4 py-2 text-right font-semibold {{ $mvt->type == 'entree' ? 'text-green-600' : 'text-red-600' }}">
                            {{ number_format($mvt->quantite) }} {{ $stock->unite }}
                        </td>
                        <td class="px-4 py-2 text-sm">{{ $mvt->motif }}</td>
                        <td class="px-4 py-2 text-sm font-mono">{{ $mvt->reference ?? '-' }}</td>
                        <td class="px-4 py-2 text-sm">{{ $mvt->user->nom ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-4 py-8 text-center text-gray-500">Aucun mouvement<td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $mouvements->links() }}</div>
    </div>
</div>

<script>
    // Format de référence: PREFIX-YYYYMMDD-HHMMSS-RANDOM
    function genererReference(type) {
        const date = new Date();
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        const hours = String(date.getHours()).padStart(2, '0');
        const minutes = String(date.getMinutes()).padStart(2, '0');
        const seconds = String(date.getSeconds()).padStart(2, '0');
        const random = Math.floor(Math.random() * 1000).toString().padStart(3, '0');
        
        let prefix = '';
        let motif = '';
        
        if (type === 'ajout') {
            motif = document.getElementById('motifAjout').value;
            if (motif === 'Achat') prefix = 'ACH';
            else if (motif === 'Réapprovisionnement') prefix = 'REAP';
            else if (motif === 'Don') prefix = 'DON';
            else if (motif === 'Transfert') prefix = 'TRF';
            else if (motif === 'Inventaire') prefix = 'INV';
            else prefix = 'ENT';
            
            document.getElementById('referenceAjout').value = `${prefix}-${year}${month}${day}-${hours}${minutes}${seconds}-${random}`;
        } else {
            motif = document.getElementById('motifRetrait').value;
            if (motif === 'Utilisation') prefix = 'UTIL';
            else if (motif === 'Distribution') prefix = 'DIST';
            else if (motif === 'Péremption') prefix = 'PER';
            else if (motif === 'Perte') prefix = 'PERT';
            else if (motif === 'Transfert') prefix = 'TRF';
            else prefix = 'SORT';
            
            document.getElementById('referenceRetrait').value = `${prefix}-${year}${month}${day}-${hours}${minutes}${seconds}-${random}`;
        }
    }
    
    // Générer les références au chargement
    document.addEventListener('DOMContentLoaded', function() {
        genererReference('ajout');
        genererReference('retrait');
    });
    
    // Régénérer la référence quand le motif change
    document.getElementById('motifAjout').addEventListener('change', function() {
        genererReference('ajout');
    });
    
    document.getElementById('motifRetrait').addEventListener('change', function() {
        genererReference('retrait');
    });
    
    // Valider que la quantité ne dépasse pas le stock pour le retrait
    document.getElementById('quantiteRetrait').addEventListener('input', function() {
        const max = parseFloat(this.max);
        const value = parseFloat(this.value);
        if (value > max) {
            this.value = max;
            alert('La quantité ne peut pas dépasser le stock disponible');
        }
    });
</script>
@endsection