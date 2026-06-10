@extends('layouts.admin')

@section('title', 'Nouvelle coopérative')
@section('header', 'Créer une coopérative')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-6">
    <form action="{{ route('admin.cooperatives.store') }}" method="POST">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Nom de la coopérative -->
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-handshake text-primary mr-1"></i> Nom de la coopérative *
                </label>
                <input type="text" name="nom" required value="{{ old('nom') }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary"
                       placeholder="Ex: Coopérative Agricole de Sokodé">
            </div>
            
            <!-- Code coopérative (auto-généré, lecture seule) -->
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-barcode text-primary mr-1"></i> Code coopérative
                </label>
                <input type="text" value="Généré automatiquement" disabled
                       class="w-full px-4 py-2 border rounded-lg bg-gray-100">
            </div>
            
            <!-- Nom du responsable (NOUVEAU) -->
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-user-tie text-primary mr-1"></i> Nom du responsable *
                </label>
                <input type="text" name="nom_responsable" required value="{{ old('nom_responsable') }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary"
                       placeholder="Ex: Jean KOMLAVI">
            </div>
            
            <!-- Contact -->
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-phone text-primary mr-1"></i> Contact *
                </label>
                <input type="text" name="contact" required value="{{ old('contact') }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary"
                       placeholder="Ex: 90 00 00 00">
            </div>
            
            <!-- Email -->
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-envelope text-primary mr-1"></i> Email
                </label>
                <input type="email" name="email" value="{{ old('email') }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary"
                       placeholder="Ex: contact@cooperative.com">
            </div>
            
            <!-- Région -->
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-map-marker-alt text-primary mr-1"></i> Région *
                </label>
                <select name="region" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    <option value="">-- Sélectionnez la région --</option>
                    <option value="Centrale" {{ old('region') == 'Centrale' ? 'selected' : '' }}> Centrale</option>
                    <option value="Kara" {{ old('region') == 'Kara' ? 'selected' : '' }}> Kara</option>
                    <option value="Savanes" {{ old('region') == 'Savanes' ? 'selected' : '' }}> Savanes</option>
                </select>
            </div>
            
            <!-- Commune (NOUVEAU) -->
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-city text-primary mr-1"></i> Commune *
                </label>
                <input type="text" name="commune" required value="{{ old('commune') }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary"
                       placeholder="Ex: Commune de Sokodé 1">
            </div>
            
            <!-- Adresse / Localisation précise (remplace l'ancien champ localisation) -->
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-location-dot text-primary mr-1"></i> Adresse précise *
                </label>
                <input type="text" name="adresse" required value="{{ old('adresse') }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary"
                       placeholder="Ex: Quartier Bamabodolo, Rue 12, Sokodé">
                <p class="text-xs text-gray-500 mt-1">Nom de la rue, quartier, lieu-dit, etc.</p>
            </div>
            
            {{-- GPS / Position exacte --}}
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
            <!-- Date de création -->
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-calendar-alt text-primary mr-1"></i> Date de création *
                </label>
                <input type="date" name="date_creation" required value="{{ old('date_creation', date('Y-m-d')) }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <!-- Statut -->
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-toggle-on text-primary mr-1"></i> Statut
                </label>
                <select name="statut" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    <option value="active" {{ old('statut') == 'active' ? 'selected' : '' }}> Active</option>
                    <option value="suspendue" {{ old('statut') == 'suspendue' ? 'selected' : '' }}> Suspendue</option>
                </select>
            </div>
            
            <!-- Description -->
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-file-alt text-primary mr-1"></i> Description
                </label>
                <textarea name="description" rows="3" 
                          class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary"
                          placeholder="Présentation de la coopérative, ses objectifs, ses activités...">{{ old('description') }}</textarea>
            </div>
        </div>
        
        <!-- Boutons d'action -->
        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('admin.cooperatives.index') }}" class="px-4 py-2 border rounded-lg hover:bg-gray-50">
                <i class="fas fa-times mr-2"></i>Annuler
            </a>
            <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-secondary">
                <i class="fas fa-save mr-2"></i>Enregistrer la coopérative
            </button>
        </div>
    </form>
</div>

<script>
    function getLocation() {
        if (navigator.geolocation) {
            // Afficher un message de chargement sur le bouton si nécessaire
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