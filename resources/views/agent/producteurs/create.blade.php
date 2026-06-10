@extends('layouts.agent')

@section('title', 'Nouveau producteur')
@section('header', 'Enregistrer un producteur')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-6">
    <form action="{{ route('agent.producteurs.store') }}" method="POST" class="offline-form">
        @csrf
        
        {{-- Champ caché pour l'ID de l'agent --}}
        <input type="hidden" name="agent_terrain_id" value="{{ Auth::guard('agent')->id() }}">

        @if($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold mb-2">Nom complet *</label>
                <input type="text" name="nom_complet" required value="{{ old('nom_complet') }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Contact *</label>
                <input type="text" name="contact" required value="{{ old('contact') }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary"
                       placeholder="Numéro de téléphone">
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2">Région *</label>
                <select name="region" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    <option value="">Sélectionnez</option>
                    <option value="Centrale" {{ old('region') == 'Centrale' ? 'selected' : '' }}>Centrale</option>
                    <option value="Kara" {{ old('region') == 'Kara' ? 'selected' : '' }}>Kara</option>
                    <option value="Savanes" {{ old('region') == 'Savanes' ? 'selected' : '' }}>Savanes</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2">Commune *</label>
                <input type="text" name="commune" required value="{{ old('commune') }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary"
                       placeholder="Ex: Tchaoudjo 1, Kozah 2...">
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2">Village / Quartier</label>
                <input type="text" name="localisation" value="{{ old('localisation') }}"
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
                               class="w-full px-4 py-2 border rounded-lg bg-gray-100 focus:outline-none"
                               placeholder="0.000000">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1">Longitude</label>
                        <input type="text" name="longitude" id="lng" required readonly
                               class="w-full px-4 py-2 border rounded-lg bg-gray-100 focus:outline-none"
                               placeholder="0.000000">
                    </div>
                    <div class="flex items-end">
                        <button type="button" onclick="getLocation()" 
                                class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition flex items-center justify-center">
                            <i class="fas fa-crosshairs mr-2"></i> Me localiser ici
                        </button>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-2 italic">Note : Cliquez sur le bouton pour capturer les coordonnées GPS actuelles sur le terrain.</p>
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Culture pratiquée *</label>
                <input type="text" name="culture_pratiquee" required value="{{ old('culture_pratiquee') }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary"
                       placeholder="Ex: Maïs, Soja, Arachide...">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Superficie totale (ha) *</label>
                <input type="number" step="0.01" name="superficie_totale" required value="{{ old('superficie_totale') }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Coopérative</label>
                <select name="cooperative_id" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    <option value="">Aucune</option>
                    @foreach($cooperatives as $cooperative)
                    <option value="{{ $cooperative->id }}" {{ old('cooperative_id') == $cooperative->id ? 'selected' : '' }}>
                        {{ $cooperative->nom }}
                    </option>
                    @endforeach
                </select>
            </div>
            
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold mb-2">Notes</label>
                <textarea name="notes" rows="3" 
                          class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">{{ old('notes') }}</textarea>
            </div>
        </div>
        
        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('agent.producteurs.index') }}" class="px-4 py-2 border rounded-lg hover:bg-gray-50">Annuler</a>
            <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-secondary">
                <i class="fas fa-save mr-2"></i>Enregistrer
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
                alert("Vous devez autoriser l'accès GPS pour utiliser cette fonction.");
                break;
            case error.POSITION_UNAVAILABLE:
                alert("Informations de localisation indisponibles.");
                break;
            case error.TIMEOUT:
                alert("La demande de localisation a expiré.");
                break;
            case error.UNKNOWN_ERROR:
                alert("Une erreur inconnue est survenue.");
                break;
        }
    }
</script>
@endsection