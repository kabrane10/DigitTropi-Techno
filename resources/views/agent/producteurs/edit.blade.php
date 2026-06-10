@extends('layouts.agent')

@section('title', 'Modifier producteur')
@section('header', 'Modifier un producteur')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-6">
    <form action="{{ route('agent.producteurs.update', $producteur) }}" method="POST" class="offline-form">
        @csrf
        @method('PUT')
        
        {{-- Champ caché pour l'ID de l'agent --}}
        <input type="hidden" name="agent_terrain_id" value="{{ Auth::guard('agent')->id() }}">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold mb-2">Code producteur</label>
                <input type="text" value="{{ $producteur->code_producteur }}" disabled class="w-full px-4 py-2 border rounded-lg bg-gray-100">
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2">Nom complet *</label>
                <input type="text" name="nom_complet" required value="{{ old('nom_complet', $producteur->nom_complet) }}" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Contact *</label>
                <input type="text" name="contact" required value="{{ old('contact', $producteur->contact) }}" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2">Région *</label>
                <select name="region" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    <option value="Centrale" {{ (old('region', $producteur->region) == 'Centrale') ? 'selected' : '' }}>Centrale</option>
                    <option value="Kara" {{ (old('region', $producteur->region) == 'Kara') ? 'selected' : '' }}>Kara</option>
                    <option value="Savanes" {{ (old('region', $producteur->region) == 'Savanes') ? 'selected' : '' }}>Savanes</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2">Commune *</label>
                <input type="text" name="commune" required value="{{ old('commune', $producteur->commune) }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary"
                       placeholder="Ex: Tchaoudjo 1, Kozah 2...">
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2">Village / Quartier</label>
                <input type="text" name="localisation" required value="{{ old('localisation', $producteur->localisation) }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary"
                       placeholder="Ex: Bamabodolo, Kozah...">
            </div>

            <div class="md:col-span-2 bg-gray-50 p-4 rounded-xl border border-dashed border-gray-300">
                <label class="block text-sm font-bold mb-3 text-primary">
                    <i class="fas fa-map-marker-alt mr-1"></i> Position exacte du producteur (GPS)
                </label>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1">Latitude</label>
                        <input type="text" name="latitude" id="lat" required readonly
                               value="{{ old('latitude', $producteur->latitude) }}"
                               class="w-full px-4 py-2 border rounded-lg bg-gray-100 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1">Longitude</label>
                        <input type="text" name="longitude" id="lng" required readonly
                               value="{{ old('longitude', $producteur->longitude) }}"
                               class="w-full px-4 py-2 border rounded-lg bg-gray-100 focus:outline-none">
                    </div>
                    <div class="flex items-end">
                        <button type="button" onclick="getLocation()" 
                                class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition flex items-center justify-center">
                            <i class="fas fa-crosshairs mr-2"></i> Actualiser la position
                        </button>
                    </div>
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Culture pratiquée *</label>
                <input type="text" name="culture_pratiquee" required value="{{ old('culture_pratiquee', $producteur->culture_pratiquee) }}" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Superficie totale (ha) *</label>
                <input type="number" step="0.01" name="superficie_totale" required value="{{ old('superficie_totale', $producteur->superficie_totale) }}" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2">Statut</label>
                <select name="statut" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    <option value="actif" {{ $producteur->statut == 'actif' ? 'selected' : '' }}>Actif</option>
                    <option value="inactif" {{ $producteur->statut == 'inactif' ? 'selected' : '' }}>Inactif</option>
                    <option value="en_attente" {{ $producteur->statut == 'en_attente' ? 'selected' : '' }}>En attente</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Coopérative</label>
                <select name="cooperative_id" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    <option value="">Aucune</option>
                    @foreach($cooperatives as $cooperative)
                    <option value="{{ $cooperative->id }}" {{ $producteur->cooperative_id == $cooperative->id ? 'selected' : '' }}>{{ $cooperative->nom }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold mb-2">Notes</label>
                <textarea name="notes" rows="3" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">{{ old('notes', $producteur->notes) }}</textarea>
            </div>
        </div>
        
        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('agent.producteurs.index') }}" class="px-4 py-2 border rounded-lg hover:bg-gray-50">Annuler</a>
            <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-secondary">
                <i class="fas fa-save mr-2"></i>Mettre à jour
            </button>
        </div>
    </form>
</div>

<script>
    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition, showError, {
                enableHighAccuracy: true,
                timeout: 5000,
                maximumAge: 0
            });
        } else {
            alert("La géolocalisation n'est pas supportée par ce navigateur.");
        }
    }

    function showPosition(position) {
        document.getElementById("lat").value = position.coords.latitude;
        document.getElementById("lng").value = position.coords.longitude;
    }

    function showError(error) {
        switch(error.code) {
            case error.PERMISSION_DENIED:
                alert("Accès GPS refusé.");
                break;
            case error.POSITION_UNAVAILABLE:
                alert("Position indisponible.");
                break;
            case error.TIMEOUT:
                alert("Délai d'attente expiré.");
                break;
            default:
                alert("Une erreur est survenue.");
        }
    }
</script>
@endsection