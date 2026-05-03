@extends('layouts.admin')

@section('title', 'Modifier la photo')
@section('header', 'Modifier la photo')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-6">
    <form action="{{ route('admin.galerie.update', $galerie) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Image actuelle</label>
                <div class="mb-4">
                    {{-- J'ai modifié la ligne ci-dessous pour afficher l'image depuis Cloudinary.
                         Anciennement : <img src="{{ asset('storage/'.$galerie->image) }}" ...>
                         Maintenant, j'utilise directement l'URL de l'image qui est maintenant stockée dans la base de données. --}}
                    <img src="{{ $galerie->image }}" alt="{{ $galerie->titre }}" class="h-48 rounded-lg object-cover">
                </div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Nouvelle image (optionnel)</label>
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-primary transition">
                    <input type="file" name="image" id="image" accept="image/*" class="hidden">
                    <label for="image" class="cursor-pointer">
                        <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-2"></i>
                        <p class="text-gray-500">Cliquez pour changer l'image</p>
                    </label>
                    <div id="imagePreview" class="mt-4 hidden">
                        <img id="previewImg" src="#" alt="Prévisualisation" class="max-h-48 mx-auto rounded-lg">
                    </div>
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Titre *</label>
                <input type="text" name="titre" required value="{{ old('titre', $galerie->titre) }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Catégorie *</label>
                <select name="categorie" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary">
                    <option value="terrain" {{ $galerie->categorie == 'terrain' ? 'selected' : '' }}>Terrain</option>
                    <option value="formation" {{ $galerie->categorie == 'formation' ? 'selected' : '' }}>Formation</option>
                    <option value="recolte" {{ $galerie->categorie == 'recolte' ? 'selected' : '' }}>Récolte</option>
                    <option value="producteurs" {{ $galerie->categorie == 'producteurs' ? 'selected' : '' }}>Producteurs</option>
                    <option value="evenement" {{ $galerie->categorie == 'evenement' ? 'selected' : '' }}>Événement</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Date de prise *</label>
                <input type="date" name="date_prise" required value="{{ old('date_prise', $galerie->date_prise->format('Y-m-d')) }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Lieu</label>
                <input type="text" name="lieu" value="{{ old('lieu', $galerie->lieu) }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                <textarea name="description" rows="4" 
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary">{{ old('description', $galerie->description) }}</textarea>
            </div>
            
            <div>
                <label class="flex items-center space-x-3">
                    <input type="checkbox" name="est_publie" value="1" class="w-4 h-4 text-primary rounded" {{ $galerie->est_publie ? 'checked' : '' }}>
                    <span class="text-sm text-gray-700">Publié</span>
                </label>
            </div>
        </div>
        
        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('admin.galerie.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">Annuler</a>
            <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-secondary">Mettre à jour</button>
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