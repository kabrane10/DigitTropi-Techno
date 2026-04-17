<!-- Carte d'alerte stock réutilisable -->
<div class="bg-red-50 border-l-4 border-red-500 rounded-lg p-4 mb-3 hover:shadow-md transition-all">
    <div class="flex justify-between items-start">
        <div class="flex-1">
            <div class="flex items-center mb-2">
                <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
                <h4 class="font-semibold text-gray-800">{{ $produit }}</h4>
                <span class="ml-2 text-xs bg-red-200 text-red-800 px-2 py-0.5 rounded-full">Stock critique</span>
            </div>
            <p class="text-sm text-gray-600">Zone: <strong>{{ $zone }}</strong></p>
            <div class="mt-2 flex items-center space-x-4">
                <div>
                    <span class="text-xs text-gray-500">Stock actuel</span>
                    <p class="text-lg font-bold text-red-600">{{ number_format($stockActuel) }} {{ $unite }}</p>
                </div>
                <div>
                    <span class="text-xs text-gray-500">Seuil d'alerte</span>
                    <p class="text-sm">{{ number_format($seuilAlerte) }} {{ $unite }}</p>
                </div>
                <div>
                    <span class="text-xs text-gray-500">Dernier mouvement</span>
                    <p class="text-sm">{{ $dernierMouvement ? \Carbon\Carbon::parse($dernierMouvement)->format('d/m/Y') : '-' }}</p>
                </div>
            </div>
        </div>
        <div class="flex flex-col space-y-2">
            <a href="{{ route('admin.stocks.mouvements', $id) }}" class="text-blue-600 hover:text-blue-800 text-sm">
                <i class="fas fa-history mr-1"></i>Historique
            </a>
            <button onclick="openRetraitModal('{{ $produit }}', '{{ $zone }}', {{ $stockActuel }})" 
                    class="text-orange-600 hover:text-orange-800 text-sm">
                <i class="fas fa-minus-circle mr-1"></i>Retirer
            </button>
        </div>
    </div>
</div>