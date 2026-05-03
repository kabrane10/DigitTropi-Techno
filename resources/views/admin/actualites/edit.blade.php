@extends('layouts.admin')

@section('title', 'Modifier l\'actualité')
@section('header', 'Modifier une actualité')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-6">
    <form action="{{ route('admin.actualites.update', $actualite) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Titre -->
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-heading text-primary mr-2"></i>Titre de l'actualité *
                </label>
                <input type="text" name="titre" required value="{{ old('titre', $actualite->titre) }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20">
                @error('titre')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Catégorie -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-folder text-primary mr-2"></i>Catégorie *
                </label>
                <select name="categorie" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary">
                    <option value="campagne" {{ $actualite->categorie == 'campagne' ? 'selected' : '' }}>Campagne agricole</option>
                    <option value="evenement" {{ $actualite->categorie == 'evenement' ? 'selected' : '' }}>Événement</option>
                    <option value="formation" {{ $actualite->categorie == 'formation' ? 'selected' : '' }}>Formation</option>
                    <option value="annonce" {{ $actualite->categorie == 'annonce' ? 'selected' : '' }}>Annonce</option>
                </select>
                @error('categorie')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Date de publication -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-calendar text-primary mr-2"></i>Date de publication *
                </label>
                <input type="date" name="date_publication" required value="{{ old('date_publication', $actualite->date_publication->format('Y-m-d')) }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary">
                @error('date_publication')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Date de fin -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-calendar-times text-primary mr-2"></i>Date de fin (optionnel)
                </label>
                <input type="date" name="date_fin" value="{{ old('date_fin', $actualite->date_fin ? $actualite->date_fin->format('Y-m-d') : '') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary">
                <p class="text-xs text-gray-500 mt-1">Pour les campagnes ou événements limités dans le temps</p>
                @error('date_fin')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Lieu -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-map-marker-alt text-primary mr-2"></i>Lieu
                </label>
                <input type="text" name="lieu" value="{{ old('lieu', $actualite->lieu) }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary"
                       placeholder="Ex: Sokodé, Région Centrale">
                @error('lieu')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Image actuelle -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-image text-primary mr-2"></i>Image actuelle
                </label>
                @if($actualite->image_couverture)
                    <div class="mb-2">
                    {{-- J'ai modifié la ligne ci-dessous pour afficher l'image depuis Cloudinary.
                         Anciennement : <img src="{{ asset('storage/' . $actualite->image_couverture) }}" ...>
                         Maintenant, j'utilise directement l'URL de l'image qui est maintenant stockée dans la base de données. --}}
                        <img src="{{ $actualite->image_couverture }}" 
                             alt="{{ $actualite->titre }}" 
                             class="h-32 rounded-lg object-cover">
                    </div>
                @else
                    <div class="p-4 bg-gray-100 rounded-lg text-center">
                        <i class="fas fa-image text-gray-400 text-3xl mb-2"></i>
                        <p class="text-sm text-gray-500">Aucune image</p>
                    </div>
                @endif
            </div>
            
            <!-- Nouvelle image -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-upload text-primary mr-2"></i>Changer l'image (optionnel)
                </label>
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-primary transition">
                    <input type="file" name="image_couverture" id="image" accept="image/*" class="hidden">
                    <label for="image" class="cursor-pointer">
                        <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                        <p class="text-gray-500 text-sm">Cliquez pour changer l'image</p>
                        <p class="text-xs text-gray-400 mt-1">JPG, PNG (max 2MB)</p>
                    </label>
                    <div id="imagePreview" class="mt-3 hidden">
                        <img id="previewImg" src="#" alt="Prévisualisation" class="max-h-32 mx-auto rounded-lg">
                    </div>
                </div>
                @error('image_couverture')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Contenu -->
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-align-left text-primary mr-2"></i>Contenu *
                </label>
                <textarea name="contenu" required rows="12" 
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary font-mono text-sm">{{ old('contenu', $actualite->contenu) }}</textarea>
                @error('contenu')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Options -->
            <div>
                <label class="flex items-center space-x-3 cursor-pointer">
                    <input type="checkbox" name="est_en_avant" value="1" 
                           class="w-4 h-4 text-primary rounded focus:ring-primary"
                           {{ $actualite->est_en_avant ? 'checked' : '' }}>
                    <span class="text-sm text-gray-700">
                        <i class="fas fa-star text-yellow-500 mr-1"></i>
                        Mettre en avant (apparaîtra en haut de page)
                    </span>
                </label>
            </div>
            
            <div>
                <label class="flex items-center space-x-3 cursor-pointer">
                    <input type="checkbox" name="est_publie" value="1" 
                           class="w-4 h-4 text-primary rounded focus:ring-primary"
                           {{ $actualite->est_publie ? 'checked' : '' }}>
                    <span class="text-sm text-gray-700">
                        <i class="fas fa-globe text-primary mr-1"></i>
                        Publié (visible sur le site)
                    </span>
                </label>
            </div>
        </div>
        
        <!-- Actions -->
        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('admin.actualites.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                Annuler
            </a>
            <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-secondary transition">
                <i class="fas fa-save mr-2"></i>Mettre à jour
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