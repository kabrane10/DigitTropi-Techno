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
    // Optionnel: Auto-remplissage des coordonnées GPS à partir de l'adresse (geocoding)
    // Fonction utilitaire pour obtenir les coordonnées à partir de l'adresse
    async function geocodeAddress() {
        const adresse = document.querySelector('input[name="adresse"]').value;
        const commune = document.querySelector('input[name="commune"]').value;
        const region = document.querySelector('select[name="region"]').value;
        
        if (!adresse) return;
        
        const adresseComplete = `${adresse}, ${commune}, ${region}, Togo`;
        
        // Note: Vous pouvez intégrer une API de géocodage ici (Google Maps, OpenStreetMap Nominatim)
        // Exemple avec Nominatim (OpenStreetMap) - à utiliser avec modération
        try {
            const response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(adresseComplete)}&limit=1`);
            const data = await response.json();
            if (data && data.length > 0) {
                document.querySelector('input[name="latitude"]').value = parseFloat(data[0].lat).toFixed(6);
                document.querySelector('input[name="longitude"]').value = parseFloat(data[0].lon).toFixed(6);
            }
        } catch (error) {
            console.error('Erreur de géocodage:', error);
        }
    }
    
    // Écouter le blur sur l'adresse pour auto-remplir les coordonnées
    // document.querySelector('input[name="adresse"]')?.addEventListener('blur', geocodeAddress);
</script>
@endsection