@extends('layouts.admin')

@section('title', 'Détails crédit')
@section('header', 'Fiche crédit agricole')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- ============================================================ -->
    <!-- COLONNE PRINCIPALE (2/3) - INFORMATIONS GÉNÉRALES             -->
    <!-- ============================================================ -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Bouton d'impression -->
        <div class="flex justify-end mb-4">
            <a href="{{ route('admin.credits.print', $credit) }}" target="_blank" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition">
                <i class="fas fa-print mr-2"></i>Imprimer la fiche
            </a>
        </div>
        <!-- 1. Informations générales -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex justify-between items-start mb-4">
                <h3 class="text-lg font-semibold">Informations du crédit</h3>
                <span class="px-3 py-1 rounded-full text-sm 
                    @if($credit->statut == 'actif') bg-yellow-100 text-yellow-800
                    @elseif($credit->statut == 'rembourse') bg-green-100 text-green-800
                    @else bg-red-100 text-red-800
                    @endif">
                    {{ $credit->statut }}
                </span>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-gray-500 text-sm">Code crédit</label>
                    <p class="font-semibold">{{ $credit->code_credit }}</p>
                </div>
                <div>
                    <label class="text-gray-500 text-sm">Date d'octroi</label>
                    <p>{{ $credit->date_octroi->format('d/m/Y') }}</p>
                </div>
                <div>
                    <label class="text-gray-500 text-sm">Date d'échéance</label>
                    <p class="{{ $credit->est_en_retard ? 'text-red-600 font-semibold' : '' }}">
                        {{ $credit->date_echeance->format('d/m/Y') }}
                        @if($credit->est_en_retard)
                            <span class="ml-2 text-xs bg-red-100 text-red-800 px-2 py-1 rounded">Retard de {{ $credit->jours_retard }} jours</span>
                        @endif
                    </p>
                </div>
                <div>
                    <label class="text-gray-500 text-sm">Durée</label>
                    <p>{{ $credit->duree_mois }} mois</p>
                </div>
                <div>
                    <label class="text-gray-500 text-sm">Taux d'intérêt</label>
                    <p>{{ $credit->taux_interet }}%</p>
                </div>
                <div>
                    <label class="text-gray-500 text-sm">Producteur</label>
                    <p class="font-semibold">{{ $credit->producteur->nom_complet }}</p>
                </div>
                <div>
                    <label class="text-gray-500 text-sm">Capital emprunté</label>
                    <p>{{ number_format($credit->montant_total, 0, ',', ' ') }} CFA</p>
                </div>
                <div>
                    <label class="text-gray-500 text-sm">Intérêts totaux</label>
                    <p class="text-blue-600">{{ number_format($montantAvecInterets - $credit->montant_total, 0, ',', ' ') }} CFA</p>
                </div>
                <div>
                    <label class="text-gray-500 text-sm">Total à rembourser</label>
                    <p class="font-bold text-primary">{{ number_format($montantAvecInterets, 0, ',', ' ') }} CFA</p>
                </div>
                <div>
                    <label class="text-gray-500 text-sm">Mensualité</label>
                    <p class="font-semibold">{{ number_format($mensualite, 0, ',', ' ') }} CFA/mois</p>
                </div>
            </div>
            
            @if($credit->conditions)
            <div class="mt-4 pt-4 border-t">
                <label class="text-gray-500 text-sm">Conditions</label>
                <p class="text-sm mt-1">{{ $credit->conditions }}</p>
            </div>
            @endif
        </div>
        
        <!-- 2. Formulaire de remboursement -->
        @if($credit->statut == 'actif' && $credit->montant_restant > 0)
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold mb-4">Enregistrer un remboursement</h3>
            <form action="{{ route('admin.credits.remboursement', $credit) }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold mb-2">Montant (CFA) *</label>
                        <input type="number" name="montant" id="montant_remboursement" required 
                               min="100" max="{{ $resteAPayer }}"
                               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary"
                               placeholder="Montant à rembourser">
                        <p class="text-xs text-gray-500 mt-1">Maximum : {{ number_format($resteAPayer, 0, ',', ' ') }} CFA</p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-2">Mode de paiement *</label>
                        <select name="mode_paiement" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                            <option value="especes">Espèces</option>
                            <option value="virement">Virement bancaire</option>
                            <option value="mobile_money">Mobile Money</option>
                            <option value="prelevement_sur_collecte">Prélèvement sur collecte</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-2">Référence</label>
                        <input type="text" name="reference" 
                               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary"
                               placeholder="Numéro de transaction">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold mb-2">Notes</label>
                        <textarea name="notes" rows="2" 
                                  class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary"
                                  placeholder="Observations..."></textarea>
                    </div>
                </div>
                <div class="mt-4 flex justify-end">
                    <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition">
                        <i class="fas fa-money-bill-wave mr-2"></i>Enregistrer le paiement
                    </button>
                </div>
            </form>
        </div>
        @endif
        
        <!-- 3. Historique des remboursements -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold mb-4"> Historique des remboursements</h3>
            @if($credit->remboursements->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs">Date</th>
                            <th class="px-4 py-2 text-right text-xs">Montant</th>
                            <th class="px-4 py-2 text-left text-xs">Mode</th>
                            <th class="px-4 py-2 text-left text-xs">Type</th>
                            <th class="px-4 py-2 text-left text-xs">Référence</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach($credit->remboursements as $remboursement)
                        <tr>
                            <td class="px-4 py-2 text-sm">{{ $remboursement->date_remboursement->format('d/m/Y') }}</td>
                            <td class="px-4 py-2 text-right text-sm font-semibold text-green-600">{{ number_format($remboursement->montant, 0, ',', ' ') }} CFA</td>
                            <td class="px-4 py-2 text-sm">{{ $remboursement->mode_paiement }}</td>
                            <td class="px-4 py-2 text-sm">{{ $remboursement->type }}</td>
                            <td class="px-4 py-2 text-sm">{{ $remboursement->reference ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50">
                        <tr>
                            <td class="px-4 py-2 font-semibold">Total remboursé</td>
                            <td class="px-4 py-2 text-right font-bold text-green-600">{{ number_format($montantRembourse, 0, ',', ' ') }} CFA</td>
                            <td colspan="3"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            @else
            <p class="text-gray-500 text-center py-4">Aucun remboursement enregistré</p>
            @endif
        </div>
    </div>
    
    <!-- ============================================================ -->
    <!-- SIDEBAR (1/3) - STATISTIQUES ET INDICATEURS                  -->
    <!-- ============================================================ -->
    <div class="space-y-6">
        
        <!-- Carte capital emprunté -->
        <div class="bg-gradient-to-r from-primary to-secondary rounded-xl p-6 text-white">
            <p class="text-sm opacity-90">Capital emprunté</p>
            <p class="text-3xl font-bold">{{ number_format($credit->montant_total, 0, ',', ' ') }} CFA</p>
        </div>
        
        <!-- Carte intérêts -->
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl p-6 text-white">
            <p class="text-sm opacity-90">Intérêts totaux</p>
            <p class="text-2xl font-bold">{{ number_format($montantAvecInterets - $credit->montant_total, 0, ',', ' ') }} CFA</p>
            <p class="text-xs opacity-75">Taux: {{ $credit->taux_interet }}%</p>
        </div>
        
        <!-- Carte total à rembourser -->
        <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl p-6 text-white">
            <p class="text-sm opacity-90">Total à rembourser</p>
            <p class="text-3xl font-bold">{{ number_format($montantAvecInterets, 0, ',', ' ') }} CFA</p>
        </div>
        
        <!-- Carte déjà remboursé -->
        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl p-6 text-white">
            <p class="text-sm opacity-90">Déjà remboursé</p>
            <p class="text-3xl font-bold">{{ number_format($montantRembourse, 0, ',', ' ') }} CFA</p>
            <div class="w-full bg-white/30 rounded-full h-2 mt-2">
                <div class="bg-white h-2 rounded-full" style="width: {{ $tauxRemboursement }}%"></div>
            </div>
            <p class="text-sm opacity-80 mt-1">{{ number_format($tauxRemboursement, 1) }}% du total</p>
        </div>
        
        <!-- Carte reste à payer -->
        <div class="bg-gradient-to-r from-orange-500 to-red-500 rounded-xl p-6 text-white">
            <p class="text-sm opacity-90">Reste à payer</p>
            <p class="text-3xl font-bold">{{ number_format($resteAPayer, 0, ',', ' ') }} CFA</p>
        </div>
        
        <!-- Prochaines échéances -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h4 class="font-semibold text-dark mb-3">Prochaines échéances</h4>
            <div class="space-y-2">
                @php
                    $prochainesEcheances = [];
                    $now = now();
                    foreach($amortissement as $echeance) {
                        $dateEcheance = \Carbon\Carbon::createFromFormat('d/m/Y', $echeance['date']);
                        if ($dateEcheance->isFuture() && $echeance['capital_restant'] > 0) {
                            $prochainesEcheances[] = $echeance;
                        }
                        if (count($prochainesEcheances) >= 3) break;
                    }
                @endphp
                @forelse($prochainesEcheances as $echeance)
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <div>
                        <p class="text-sm font-medium">{{ $echeance['date'] }}</p>
                        <p class="text-xs text-green-600">Mois n°{{ $echeance['mois'] }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-primary">{{ number_format($echeance['mensualite'], 0, ',', ' ') }} CFA</p>
                        <p class="text-xs text-gray-500">dont {{ number_format($echeance['interets'], 0, ',', ' ') }} CFA d'intérêts</p>
                    </div>
                </div>
                @empty
                <p class="text-gray-500 text-sm text-center py-4">Aucune échéance à venir</p>
                @endforelse
            </div>
        </div>
        
        <!-- Récapitulatif des paiements -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h4 class="font-semibold text-dark mb-3"> Récapitulatif</h4>
            <div class="space-y-2">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Nombre de mensualités</span>
                    <span class="font-semibold text-green-600">{{ $credit->duree_mois }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Mensualité calculée</span>
                    <span class="font-semibold text-red-600 ">{{ number_format($mensualite, 0, ',', ' ') }} CFA</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Paiements effectués</span>
                    <span class="font-semibold text-green-600">{{ $credit->remboursements->count() }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Paiements restants</span>
                    <span class="font-semibold text-green-600">{{ max(0, $credit->duree_mois - $credit->remboursements->count()) }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ============================================================ -->
<!-- TABLEAU D'AMORTISSEMENT     -->
<!-- ============================================================ -->
<div class="mt-6">
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold">Tableau d'amortissement</h3>
            <button onclick="toggleTableau()" class="text-primary text-sm hover:underline">
                <i class="fas fa-chevron-up mr-1"></i> Masquer
            </button>
        </div>
        
        <div id="tableauAmortissement">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 py-2 text-center">Mois</th>
                            <th class="px-3 py-2 text-center">Date échéance</th>
                            <th class="px-3 py-2 text-right">Mensualité</th>
                            <th class="px-3 py-2 text-right">Intérêts</th>
                            <th class="px-3 py-2 text-right">Capital</th>
                            <th class="px-3 py-2 text-right">Capital restant</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach($amortissement as $echeance)
                        <tr class="{{ $echeance['capital_restant'] <= 0 ? 'bg-green-50' : '' }}">
                            <td class="px-3 py-2 text-center font-medium">{{ $echeance['mois'] }}</td>
                            <td class="px-3 py-2 text-center">{{ $echeance['date'] }}</td>
                            <td class="px-3 py-2 text-right">{{ number_format($echeance['mensualite'], 0, ',', ' ') }} CFA</td>
                            <td class="px-3 py-2 text-right">{{ number_format($echeance['interets'], 0, ',', ' ') }} CFA</td>
                            <td class="px-3 py-2 text-right">{{ number_format($echeance['capital'], 0, ',', ' ') }} CFA</td>
                            <td class="px-3 py-2 text-right font-semibold {{ $echeance['capital_restant'] > 0 ? 'text-orange-600' : 'text-green-600' }}">
                                {{ number_format($echeance['capital_restant'], 0, ',', ' ') }} CFA
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50 font-semibold">
                        <tr>
                            <td colspan="2" class="px-3 py-2">TOTAUX</td>
                            <td class="px-3 py-2 text-right">{{ number_format($montantAvecInterets, 0, ',', ' ') }} CFA</td>
                            <td class="px-3 py-2 text-right">{{ number_format($montantAvecInterets - $credit->montant_total, 0, ',', ' ') }} CFA</td>
                            <td class="px-3 py-2 text-right">{{ number_format($credit->montant_total, 0, ',', ' ') }} CFA</td>
                            <td class="px-3 py-2 text-right">-</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
            <!-- Légende -->
            <div class="mt-4 p-3 bg-gray-50 rounded-lg text-xs text-gray-500">
                <p><i class="fas fa-info-circle mr-1"></i> Le tableau d'amortissement détaille chaque mensualité :</p>
                <ul class="list-disc list-inside ml-4 mt-1">
                    <li><span class="font-semibold">Intérêts</span> : Coût du crédit calculé sur le capital restant</li>
                    <li><span class="font-semibold">Capital</span> : Partie qui rembourse le capital emprunté</li>
                    <li><span class="font-semibold">Capital restant</span> : Montant encore dû après ce paiement</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
    // Mettre à jour l'affichage du montant maximum
    const montantInput = document.getElementById('montant_remboursement');
    if (montantInput) {
        montantInput.addEventListener('input', function() {
            const max = parseFloat(this.max);
            const value = parseFloat(this.value);
            if (value > max) {
                this.value = max;
            }
        });
    }
    
    // Toggle pour afficher/masquer le tableau d'amortissement
    function toggleTableau() {
        const tableau = document.getElementById('tableauAmortissement');
        const btn = event.currentTarget;
        
        if (tableau.style.display === 'none') {
            tableau.style.display = 'block';
            btn.innerHTML = '<i class="fas fa-chevron-up mr-1"></i> Masquer';
        } else {
            tableau.style.display = 'none';
            btn.innerHTML = '<i class="fas fa-chevron-down mr-1"></i> Afficher le tableau';
        }
    }
</script>

<style>
    #tableauAmortissement {
        transition: all 0.3s ease;
    }
</style>
@endsection