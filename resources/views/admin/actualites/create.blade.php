@extends('layouts.admin')

@section('title', 'Nouvelle actualité')
@section('header', 'Créer une actualité')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-6">
    <form action="{{ route('admin.actualites.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-heading text-primary mr-2"></i>Titre de l'actualité *
                </label>
                <input type="text" name="titre" required 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20"
                       placeholder="Ex: Lancement de la campagne agricole 2025">
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
                    <option value="campagne">Campagne agricole</option>
                    <option value="evenement">Événement</option>
                    <option value="formation">Formation</option>
                    <option value="annonce">Annonce</option>
                </select>
                @error('categorie')
                    <p class="text-red-500 text-xs mt-1">{{ session('message') }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-calendar text-primary mr-2"></i>Date de publication *
                </label>
                <input type="date" name="date_publication" required 
                       value="{{ date('Y-m-d') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary">
                @error('date_publication')
                    <p class="text-red-500 text-xs mt-1">{{ session('message') }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-calendar-times text-primary mr-2"></i>Date de fin (optionnel)
                </label>
                <input type="date" name="date_fin" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary">
                <p class="text-xs text-gray-500 mt-1">Pour les campagnes ou événements limités dans le temps</p>
                @error('date_fin')
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
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-image text-primary mr-2"></i>Image de couverture
                </label>
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-primary transition">
                    <input type="file" name="image_couverture" id="image" accept="image/*" class="hidden">
                    <label for="image" class="cursor-pointer">
                        <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                        <p class="text-gray-500 text-sm">Cliquez pour ajouter une image</p>
                    </label>
                    <div id="imagePreview" class="mt-3 hidden">
                        <img id="previewImg" src="#" alt="Prévisualisation" class="max-h-32 mx-auto rounded-lg">
                    </div>
                </div>
                @error('image_couverture')
                    <p class="text-red-500 text-xs mt-1">{{ session('message') }}</p>
                @enderror
            </div>
            
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-align-left text-primary mr-2"></i>Contenu *
                </label>
                <textarea name="contenu" required rows="12" 
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary font-mono text-sm"
                          placeholder="Rédigez le contenu de votre actualité ici..."></textarea>
                @error('contenu')
                    <p class="text-red-500 text-xs mt-1">{{ session('message') }}</p>
                @enderror
            </div>
            
            <div>
                <label class="flex items-center space-x-3">
                    <input type="checkbox" name="est_en_avant" value="1" class="w-4 h-4 text-primary rounded focus:ring-primary">
                    <span class="text-sm text-gray-700">Mettre en avant (apparaîtra en haut de page)</span>
                </label>
            </div>
            
            <div>
                <label class="flex items-center space-x-3">
                    <input type="checkbox" name="est_publie" value="1" class="w-4 h-4 text-primary rounded focus:ring-primary" checked>
                    <span class="text-sm text-gray-700">Publier immédiatement</span>
                </label>
            </div>
        </div>
        
        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('admin.actualites.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                Annuler
            </a>
            <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-secondary transition">
                <i class="fas fa-save mr-2"></i>Publier l'actualité
            </button>
        </div>
    </form>
</div>

<script>
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