@extends('layouts.admin')

@section('title', 'Modifier coopérative')
@section('header', 'Modifier une coopérative')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-6">
    <form action="{{ route('admin.cooperatives.update', $cooperative) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Nom de la coopérative -->
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-handshake text-primary mr-1"></i> Nom de la coopérative *
                </label>
                <input type="text" name="nom" required value="{{ old('nom', $cooperative->nom) }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <!-- Code coopérative (lecture seule) -->
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-barcode text-primary mr-1"></i> Code coopérative
                </label>
                <input type="text" value="{{ $cooperative->code_cooperative }}" disabled
                       class="w-full px-4 py-2 border rounded-lg bg-gray-100">
            </div>
            
            <!-- Nom du responsable -->
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-user-tie text-primary mr-1"></i> Nom du responsable *
                </label>
                <input type="text" name="nom_responsable" required value="{{ old('nom_responsable', $cooperative->nom_responsable) }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <!-- Contact -->
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-phone text-primary mr-1"></i> Contact *
                </label>
                <input type="text" name="contact" required value="{{ old('contact', $cooperative->contact) }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <!-- Email -->
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-envelope text-primary mr-1"></i> Email
                </label>
                <input type="email" name="email" value="{{ old('email', $cooperative->email) }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <!-- Région -->
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-map-marker-alt text-primary mr-1"></i> Région *
                </label>
                <select name="region" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    <option value="Centrale" {{ $cooperative->region == 'Centrale' ? 'selected' : '' }}> Centrale</option>
                    <option value="Kara" {{ $cooperative->region == 'Kara' ? 'selected' : '' }}> Kara</option>
                    <option value="Savanes" {{ $cooperative->region == 'Savanes' ? 'selected' : '' }}> Savanes</option>
                </select>
            </div>
            
            <!-- Commune -->
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-city text-primary mr-1"></i> Commune *
                </label>
                <input type="text" name="commune" required value="{{ old('commune', $cooperative->commune) }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <!-- Adresse précise -->
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-location-dot text-primary mr-1"></i> Adresse précise *
                </label>
                <input type="text" name="adresse" required value="{{ old('adresse', $cooperative->adresse ?? $cooperative->localisation) }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
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
                               value="{{ old('latitude', $cooperative->latitude) }}"
                               class="w-full px-4 py-2 border rounded-lg bg-gray-100 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1">Longitude</label>
                        <input type="text" name="longitude" id="lng" required readonly
                               value="{{ old('longitude', $cooperative->longitude) }}"
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
            <!-- Date de création -->
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-calendar-alt text-primary mr-1"></i> Date de création *
                </label>
                <input type="date" name="date_creation" required value="{{ old('date_creation', $cooperative->date_creation->format('Y-m-d')) }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <!-- Statut -->
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-toggle-on text-primary mr-1"></i> Statut
                </label>
                <select name="statut" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    <option value="active" {{ $cooperative->statut == 'active' ? 'selected' : '' }}> Active</option>
                    <option value="suspendue" {{ $cooperative->statut == 'suspendue' ? 'selected' : '' }}> Suspendue</option>
                </select>
            </div>
            
            <!-- Description -->
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-file-alt text-primary mr-1"></i> Description
                </label>
                <textarea name="description" rows="3" 
                          class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary"
                          placeholder="Présentation de la coopérative...">{{ old('description', $cooperative->description) }}</textarea>
            </div>
        </div>
        
        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('admin.cooperatives.index') }}" class="px-4 py-2 border rounded-lg hover:bg-gray-50">Annuler</a>
            <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-secondary">
                <i class="fas fa-save mr-2"></i>Mettre à jour
            </button>
        </div>
    </form>
</div>
@endsection