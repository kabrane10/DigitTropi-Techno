<!-- Modal Retrait Stock -->
<div id="retraitModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl p-6 max-w-md w-full">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold">Retirer du stock</h3>
            <button onclick="closeRetraitModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form action="{{ route('admin.stocks.sortie') }}" method="POST">
            @csrf
            <input type="hidden" name="produit" id="retrait_produit">
            <input type="hidden" name="zone" id="retrait_zone">
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold mb-1">Produit</label>
                    <p id="retrait_produit_label" class="text-gray-700 font-medium"></p>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold mb-1">Zone</label>
                    <p id="retrait_zone_label" class="text-gray-700"></p>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold mb-1">Quantité à retirer *</label>
                    <input type="number" step="0.01" name="quantite" id="retrait_quantite" required 
                           class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    <p id="stockMax" class="text-xs text-gray-500 mt-1"></p>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold mb-1">Motif *</label>
                    <select name="motif" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-primary">
                        <option value="">Sélectionnez un motif</option>
                        <option value="Vente">Vente</option>
                        <option value="Distribution">Distribution aux producteurs</option>
                        <option value="Perte">Perte / Avarie</option>
                        <option value="Transfert">Transfert vers autre zone</option>
                        <option value="Autre">Autre</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold mb-1">Description détaillée</label>
                    <textarea name="description" rows="2" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-primary"
                              placeholder="Précisez le motif si nécessaire..."></textarea>
                </div>
            </div>
            
            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" onclick="closeRetraitModal()" class="px-4 py-2 border rounded-lg hover:bg-gray-50">
                    Annuler
                </button>
                <button type="submit" class="bg-orange-500 text-white px-4 py-2 rounded-lg hover:bg-orange-600">
                    <i class="fas fa-minus-circle mr-2"></i>Retirer
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openRetraitModal(produit, zone, stockActuel) {
        document.getElementById('retrait_produit').value = produit;
        document.getElementById('retrait_zone').value = zone;
        document.getElementById('retrait_produit_label').textContent = produit;
        document.getElementById('retrait_zone_label').textContent = zone;
        document.getElementById('stockMax').textContent = 'Stock maximum disponible : ' + stockActuel.toLocaleString() + ' kg';
        document.getElementById('retrait_quantite').max = stockActuel;
        document.getElementById('retraitModal').classList.remove('hidden');
        document.getElementById('retraitModal').classList.add('flex');
    }
    
    function closeRetraitModal() {
        document.getElementById('retraitModal').classList.add('hidden');
        document.getElementById('retraitModal').classList.remove('flex');
        document.getElementById('retrait_quantite').value = '';
    }
</script>