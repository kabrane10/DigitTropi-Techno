@extends('layouts.admin')

@section('title', 'Ajouter une photo')
@section('header', 'Ajouter une photo à la galerie')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-6">
    <form action="{{ route('admin.galerie.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-image text-primary mr-2"></i>Image *
                </label>
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-primary transition">
                    <input type="file" name="image" id="image" accept="image/*" class="hidden" required>
                    <label for="image" class="cursor-pointer">
                        <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-2"></i>
                        <p class="text-gray-500">Cliquez ou glissez une image ici</p>
                        <p class="text-xs text-gray-400 mt-1">JPG, PNG, GIF (max 5MB)</p>
                    </label>
                    <div id="imagePreview" class="mt-4 hidden">
                        <img id="previewImg" src="#" alt="Prévisualisation" class="max-h-48 mx-auto rounded-lg">
                    </div>
                </div>
                @error('image')
                    <p class="text-red-500 text-xs mt-1">{{ session('message') }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-tag text-primary mr-2"></i>Titre *
                </label>
                <input type="text" name="titre" required 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition"
                       placeholder="Ex: Formation des producteurs à Sokodé">
                @error('titre')
                    <p class="text-red-500 text-xs mt-1">{{ session('message') }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-folder text-primary mr-2"></i>Catégorie *
                </label>
                <select name="categorie" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary">
                    <option value="">Sélectionnez une catégorie</option>
                    <option value="terrain">Terrain</option>
                    <option value="formation">Formation</option>
                    <option value="recolte">Récolte</option>
                    <option value="producteurs">Producteurs</option>
                    <option value="evenement">Événement</option>
                </select>
                @error('categorie')
                    <p class="text-red-500 text-xs mt-1">{{ session('message') }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-calendar text-primary mr-2"></i>Date de prise *
                </label>
                <input type="date" name="date_prise" required 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary">
                @error('date_prise')
                    <p class="text-red-500 text-xs mt-1">{{ session('message') }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-map-marker-alt text-primary mr-2"></i>Lieu
                </label>
                <input type="text" name="lieu" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary"
                       placeholder="Ex: Sokodé, Région Centrale">
                @error('lieu')
                    <p class="text-red-500 text-xs mt-1">{{ session('message') }}</p>
                @enderror
            </div>
            
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-align-left text-primary mr-2"></i>Description
                </label>
                <textarea name="description" rows="4" 
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary"
                          placeholder="Décrivez cette photo..."></textarea>
                @error('description')
                    <p class="text-red-500 text-xs mt-1">{{ session('message') }}</p>
                @enderror
            </div>
            
            <div>
                <label class="flex items-center space-x-3">
                    <input type="checkbox" name="est_publie" value="1" class="w-4 h-4 text-primary rounded focus:ring-primary" checked>
                    <span class="text-sm text-gray-700">Publier immédiatement</span>
                </label>
            </div>
        </div>
        
        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('admin.galerie.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                Annuler
            </a>
            <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-secondary transition">
                <i class="fas fa-save mr-2"></i>Enregistrer
            </button>
        </div>
    </form>
</div>

<script>
    // Prévisualisation de l'image
    document.getElementById('image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('previewImg').src = e.target.result;
                document.getElementById('imagePreview').classList.remove('hidden');
            }
            reader.readAsDataURL(file);
        }
    });
</script>
@endsection