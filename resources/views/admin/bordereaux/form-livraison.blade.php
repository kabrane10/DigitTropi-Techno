@extends('layouts.admin')

@section('title', 'Bordereau de livraison')
@section('header', 'Générer un bordereau de livraison')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-6">
    <form action="{{ route('admin.bordereaux.generer-livraison') }}" method="POST">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Informations destinataire -->
            <div class="md:col-span-2">
                <h3 class="text-lg font-semibold text-dark mb-4 border-b pb-2">
                    <i class="fas fa-user-tie text-primary mr-2"></i>Informations destinataire
                </h3>
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Nom du destinataire *</label>
                <input type="text" name="destinataire" required 
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary"
                       placeholder="Nom complet">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Téléphone destinataire</label>
                <input type="text" name="telephone" 
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary"
                       placeholder="+228 XX XX XX XX">
            </div>
            
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold mb-2">Adresse de livraison *</label>
                <textarea name="adresse_livraison" required rows="2" 
                          class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary"
                          placeholder="Adresse complète de livraison"></textarea>
            </div>
            
            <!-- Informations livraison -->
            <div class="md:col-span-2 mt-2">
                <h3 class="text-lg font-semibold text-dark mb-4 border-b pb-2">
                    <i class="fas fa-truck text-primary mr-2"></i>Informations livraison
                </h3>
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Date de livraison prévue *</label>
                <input type="date" name="date_livraison_prevue" required value="{{ date('Y-m-d', strtotime('+3 days')) }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Transporteur *</label>
                <select name="transporteur" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    <option value="">Sélectionnez un transporteur</option>
                    <option value="Transport Express Togo">Transport Express Togo</option>
                    <option value="Logistique Plus">Logistique Plus</option>
                    <option value="Cargo Service">Cargo Service</option>
                    <option value="Transporteur indépendant">Transporteur indépendant</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Numéro de véhicule</label>
                <input type="text" name="immatriculation" 
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary"
                       placeholder="Ex: TG-1234-AB">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Nom du conducteur</label>
                <input type="text" name="conducteur" 
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary"
                       placeholder="Nom du chauffeur">
            </div>
            
            <!-- Liste des produits -->
            <div class="md:col-span-2 mt-2">
                <div class="flex justify-between items-center mb-3">
                    <h3 class="text-lg font-semibold text-dark">
                        <i class="fas fa-boxes text-primary mr-2"></i>Produits à livrer
                    </h3>
                    <button type="button" onclick="ajouterProduit()" class="bg-green-500 text-white px-3 py-1 rounded-lg text-sm hover:bg-green-600">
                        <i class="fas fa-plus mr-1"></i>Ajouter produit
                    </button>
                </div>
                
                <div id="produits-container" class="space-y-3">
                    <div class="produit-item grid grid-cols-1 md:grid-cols-4 gap-3 p-3 bg-gray-50 rounded-lg">
                        <div>
                            <label class="block text-xs font-semibold mb-1">Produit *</label>
                            <input type="text" name="produits[0][nom]" required 
                                   class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-primary text-sm"
                                   placeholder="Nom du produit">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold mb-1">Quantité *</label>
                            <input type="number" step="0.01" name="produits[0][quantite]" required 
                                   class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-primary text-sm"
                                   placeholder="Quantité">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold mb-1">Unité</label>
                            <select name="produits[{{ 0 }}][unite]" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-primary text-sm">
                                <option value="kg">kg</option>
                                <option value="tonne">tonne</option>
                                <option value="sac">sac</option>
                                <option value="carton">carton</option>
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button type="button" onclick="supprimerProduit(this)" class="text-red-500 hover:text-red-700">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Observations -->
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold mb-2">Observations</label>
                <textarea name="observations" rows="3" 
                          class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary"
                          placeholder="Instructions particulières, notes..."></textarea>
            </div>
        </div>
        
        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('admin.bordereaux.index') }}" class="px-4 py-2 border rounded-lg hover:bg-gray-50">Annuler</a>
            <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-secondary">
                <i class="fas fa-file-alt mr-2"></i>Générer le bordereau
            </button>
        </div>
    </form>
</div>

<script>
    let produitIndex = 1;
    
    function ajouterProduit() {
        const container = document.getElementById('produits-container');
        const newDiv = document.createElement('div');
        newDiv.className = 'produit-item grid grid-cols-1 md:grid-cols-4 gap-3 p-3 bg-gray-50 rounded-lg';
        newDiv.innerHTML = `
            <div>
                <label class="block text-xs font-semibold mb-1">Produit *</label>
                <input type="text" name="produits[${produitIndex}][nom]" required 
                       class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-primary text-sm"
                       placeholder="Nom du produit">
            </div>
            <div>
                <label class="block text-xs font-semibold mb-1">Quantité *</label>
                <input type="number" step="0.01" name="produits[${produitIndex}][quantite]" required 
                       class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-primary text-sm"
                       placeholder="Quantité">
            </div>
            <div>
                <label class="block text-xs font-semibold mb-1">Unité</label>
                <select name="produits[${produitIndex}][unite]" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-primary text-sm">
                    <option value="kg">kg</option>
                    <option value="tonne">tonne</option>
                    <option value="sac">sac</option>
                    <option value="carton">carton</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="button" onclick="supprimerProduit(this)" class="text-red-500 hover:text-red-700">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        `;
        container.appendChild(newDiv);
        produitIndex++;
    }
    
    function supprimerProduit(btn) {
        const produitItem = btn.closest('.produit-item');
        if (document.querySelectorAll('.produit-item').length > 1) {
            produitItem.remove();
        } else {
            alert('Vous devez avoir au moins un produit');
        }
    }
</script>
@endsection