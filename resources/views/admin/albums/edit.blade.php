@extends('layouts.admin')

@section('title', 'Modifier album')
@section('header', 'Modifier l\'album : ' . $album->titre)

@section('content')
<div class="bg-white rounded-xl shadow-sm p-6">
    <form action="{{ route('admin.albums.update', $album) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Titre -->
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Titre de l'album *</label>
                <input type="text" name="titre" required value="{{ old('titre', $album->titre) }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <!-- Catégorie -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Catégorie *</label>
                <select name="categorie" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary">
                    <option value="terrain" {{ $album->categorie == 'terrain' ? 'selected' : '' }}>Terrain</option>
                    <option value="formation" {{ $album->categorie == 'formation' ? 'selected' : '' }}>Formation</option>
                    <option value="recolte" {{ $album->categorie == 'recolte' ? 'selected' : '' }}>Récolte</option>
                    <option value="producteurs" {{ $album->categorie == 'producteurs' ? 'selected' : '' }}>Producteurs</option>
                    <option value="evenement" {{ $album->categorie == 'evenement' ? 'selected' : '' }}>Événement</option>
                </select>
            </div>
            
            <!-- Date -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Date de l'événement *</label>
                <input type="date" name="date_evenement" required value="{{ old('date_evenement', $album->date_evenement->format('Y-m-d')) }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <!-- Lieu -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Lieu</label>
                <input type="text" name="lieu" value="{{ old('lieu', $album->lieu) }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <!-- Description -->
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                <textarea name="description" rows="3" 
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary">{{ old('description', $album->description) }}</textarea>
            </div>
            
            <!-- Image de couverture -->
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Image de couverture</label>
                @if($album->couverture)
                <div class="mb-3">
                    {{-- J'ai modifié la ligne ci-dessous pour afficher l'image depuis Cloudinary.
                         Anciennement : <img src="{{ asset('storage/' . $album->couverture) }}" ...>
                         Maintenant, j'utilise directement l'URL de l'image qui est maintenant stockée dans la base de données. --}}
                    <img src="{{ $album->couverture }}" class="h-32 rounded-lg object-cover">
                </div>
                @endif
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-primary transition cursor-pointer"
                     onclick="document.getElementById('couverture').click()">
                    <input type="file" name="couverture" id="couverture" accept="image/*" class="hidden">
                    <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                    <p class="text-gray-500">Changer l'image de couverture</p>
                    <div id="couverturePreview" class="mt-3 hidden">
                        <img id="couvertureImg" src="#" class="max-h-32 mx-auto rounded-lg">
                    </div>
                </div>
            </div>
            
            <!-- Statut -->
            <div>
                <label class="flex items-center space-x-3">
                    <input type="checkbox" name="est_publie" value="1" class="w-4 h-4 text-primary rounded" {{ $album->est_publie ? 'checked' : '' }}>
                    <span class="text-sm text-gray-700">Album publié (visible sur le site)</span>
                </label>
            </div>
        </div>
        
        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('admin.albums.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">Annuler</a>
            <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-secondary">
                <i class="fas fa-save mr-2"></i>Mettre à jour
            </button>
        </div>
    </form>
</div>

<script>
    document.getElementById('couverture').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('couvertureImg').src = e.target.result;
                document.getElementById('couverturePreview').classList.remove('hidden');
            }
            reader.readAsDataURL(file);
        }
    });
</script>
@endsection